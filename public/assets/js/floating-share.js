(function () {
  'use strict';

  function getMetaContent(selector) {
    var el = document.querySelector(selector);
    return el && el.getAttribute('content') ? el.getAttribute('content') : '';
  }

  function buildShareData(root) {
    var url = root.getAttribute('data-share-url') || window.location.href;
    var title = root.getAttribute('data-share-title') || document.title || '';
    var text =
      root.getAttribute('data-share-text') ||
      getMetaContent('meta[property="og:description"]') ||
      getMetaContent('meta[name="description"]') ||
      '';

    // keep it short-ish for social platforms
    text = (text || '').trim();
    title = (title || '').trim();
    url = (url || '').trim();

    return { url: url, title: title, text: text };
  }

  function setHref(el, href) {
    if (!el) return;
    el.setAttribute('href', href);
  }

  function initShareFloat(root) {
    var toggleBtn = root.querySelector('[data-share-toggle]');
    var menu = root.querySelector('[data-share-menu]');

    if (!toggleBtn || !menu) return;

    var data = buildShareData(root);
    var encodedUrl = encodeURIComponent(data.url);
    var encodedTitle = encodeURIComponent(data.title);
    var message = (data.title ? data.title + ' - ' : '') + data.url;
    var encodedMessage = encodeURIComponent(message);
    var encodedEmailBody = encodeURIComponent((data.title ? data.title + '\n\n' : '') + data.url);

    // assign share URLs
    setHref(root.querySelector('[data-share="email"]'), 'mailto:?subject=' + encodedTitle + '&body=' + encodedEmailBody);
    setHref(root.querySelector('[data-share="whatsapp"]'), 'https://wa.me/?text=' + encodedMessage);
    setHref(root.querySelector('[data-share="facebook"]'), 'https://www.facebook.com/sharer/sharer.php?u=' + encodedUrl);
    setHref(root.querySelector('[data-share="x"]'), 'https://twitter.com/intent/tweet?text=' + encodedMessage);

    // "More" (native share sheet when available)
    var moreBtn = root.querySelector('[data-share="more"]');
    if (moreBtn && !('share' in navigator)) {
      moreBtn.style.display = 'none';
    }

    function open() {
      root.classList.add('is-open');
      toggleBtn.setAttribute('aria-expanded', 'true');
      menu.setAttribute('aria-hidden', 'false');
    }

    function close() {
      root.classList.remove('is-open');
      toggleBtn.setAttribute('aria-expanded', 'false');
      menu.setAttribute('aria-hidden', 'true');
    }

    function toggle() {
      if (root.classList.contains('is-open')) {
        close();
      } else {
        open();
      }
    }

    toggleBtn.addEventListener('click', function (e) {
      e.preventDefault();
      toggle();
    });

    // close on outside click
    document.addEventListener('click', function (e) {
      if (!root.classList.contains('is-open')) return;
      if (root.contains(e.target)) return;
      close();
    });

    // close on ESC
    document.addEventListener('keydown', function (e) {
      if (!root.classList.contains('is-open')) return;
      if (e.key === 'Escape') close();
    });

    // copy link
    var copyBtn = root.querySelector('[data-share="copy"]');
    if (copyBtn) {
      copyBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        try {
          if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(data.url);
          } else {
            var ta = document.createElement('textarea');
            ta.value = data.url;
            ta.setAttribute('readonly', 'readonly');
            ta.style.position = 'absolute';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
          }

          if (window.toastr && typeof window.toastr.success === 'function') {
            window.toastr.success('Link copied to clipboard');
          } else {
            alert('Link copied to clipboard');
          }
        } catch (err) {
          if (window.toastr && typeof window.toastr.error === 'function') {
            window.toastr.error('Could not copy the link');
          } else {
            alert('Could not copy the link');
          }
        }
      });
    }

    // instagram: copy link + open instagram
    var igBtn = root.querySelector('[data-share="instagram"]');
    if (igBtn) {
      igBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        if (copyBtn) {
          copyBtn.click();
        }
        window.open('https://www.instagram.com/', '_blank', 'noopener');
      });
    }

    // native share
    if (moreBtn && 'share' in navigator) {
      moreBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        try {
          await navigator.share({ title: data.title, text: data.text || data.title, url: data.url });
        } catch (_err) {
          // user cancelled or unsupported payload; ignore
        }
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var nodes = document.querySelectorAll('[data-share-float]');
    if (!nodes || !nodes.length) return;
    nodes.forEach(initShareFloat);
  });
})();

