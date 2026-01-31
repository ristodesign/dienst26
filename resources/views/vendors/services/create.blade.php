@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Service') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Service Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Service') }}</a>
      </li>
    </ul>
  </div>

  @php
    $vendor_id = Auth::guard('vendor')->user()->id;
    $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendor_id);
  @endphp
  <div class="row">
    <div class="col-md-12">
      @if ($current_package != '[]')
        @php
          $sliderImage = $current_package->number_of_service_image;
        @endphp
        @if (vendorTotalAddedService($vendor_id) >= $current_package->number_of_service_add)
          <div class="alert alert-danger text-dark">
            {{ __("You can't add more services. Please buy/extend a plan to add service") }}
          </div>
          @php
            $can_service_add = 2;
          @endphp
        @else
          @php
            $can_service_add = 1;
          @endphp
        @endif
      @else
        @php
          $pendingMemb = \App\Models\Membership::query()
              ->where([['vendor_id', '=', Auth::id()], ['status', 0]])
              ->whereYear('start_date', '<>', '9999')
              ->orderBy('id', 'DESC')
              ->first();
          $pendingPackage = isset($pendingMemb)
              ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id)
              : null;
        @endphp
        @if ($pendingPackage)
          <div class="alert alert-warning text-dark">
            {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
          </div>
          <div class="alert alert-warning text-dark">
            <strong>{{ __('Pending Package') . ':' }} </strong> {{ $pendingPackage->title }}
            <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
            <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
          </div>
        @else
          <div class="alert alert-warning text-dark">
            {{ __('Please purchase a new package / extend the current package.') }}
          </div>
        @endif
        @php
          $can_service_add = 0;
          $sliderImage = 0;
        @endphp
      @endif



      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Service') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.service_managment', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              @php
                $fontSize = '12px';
              @endphp
              <i class="fas fa-backward" style="font-size: {{ $fontSize }}"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="col-lg-12 mx-auto">
          <div class="card-body pt-4 pb-4">

            <div class="alert alert-danger pb-1 dis-none" id="service_erros">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <ul></ul>
            </div>
            <div class="row">

              <!--hier zat de image upload-->
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') }} *</strong></label>
                <form action="{{ route('vendor.service.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata"
                  class="dropzone create">
                  @csrf
                      <div class="dz-message">{{ __('Drag and drop files here to upload') }}</div>
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="text-warning mt-2 mb-0">

                  @if ($sliderImage === 999999)
                    <small>
                      {{ __('You can upload') }} {{ $sliderImage === 999999 ? __('unlimited') : $sliderImage }}
                      {{ __('images') }}.</small>
                  @else
                    <small>
                      {{ __('Please note that you can upload a maximum of') }} {{ $sliderImage }}
                      @if ($sliderImage > 1)
                        {{ __('images') }}.
                      @else
                        {{ __('image') }}
                      @endif
                    </small>
                  @endif

                </p>
                <p class="em text-danger mb-0" id="errslider_images"></p>

              </div>


              <div class="col-lg-12">
                <form id="serviceForm" action="{{ route('vendor.service_managment.store') }}" method="POST"
                  enctype="multipart/form-data">
                  @csrf
                  <div id="sliders"></div>
                  <input type="hidden" name="can_service_add" value="{{ $can_service_add }}">
                  <input type="hidden" name="vendor_id" value="{{ Auth::guard('vendor')->user()->id }}">
                  <div class="version border-0">
                    <div class="version-body">
                      <div class="row">

                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Featured Image') . '*' }}</label>
                            <br>
                            <div class="thumb-preview">
                              <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                            </div>

                            <div class="mt-3">
                              <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="img-input" name="service_image">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-9">

                              @php $currencyText = $currencyInfo->base_currency_text; @endphp
                              <div class="row">

                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Price') . '* (' . $currencyText . ')' }}</label>
                                    <input type="number" class="form-control" name="price" value="{{ old('price') }}"
                                      placeholder="{{ __('Enter Price') }}">
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>
                                      {{ __('Previous Price') . ' (' . $currencyText . ')' }}</label>
                                    <input type="number" class="form-control" name="prev_price" value="{{ old('price') }}"
                                      placeholder="{{ __('Enter Price') }}">
                                  </div>
                                </div>

                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Status') . '*' }}</label>
                                    <select name="status" class="form-control">
                                      <option selected="" disabled="">{{ __('Select a Status') }}</option>
                                      <option value="1">{{ __('Active') }}</option>
                                      <option value="0">{{ __('Deactive') }}</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Person') }}</label>
                                    <div class="selectgroup w-100">
                                      <label class="selectgroup-item">
                                        <input type="radio" name="person_type" value="1" class="selectgroup-input"
                                          checked="">
                                        <span class="selectgroup-button">{{ __('Single') }}</span>
                                      </label>

                                      <label class="selectgroup-item">
                                        <input type="radio" name="person_type" value="0" class="selectgroup-input">
                                        <span class="selectgroup-button">{{ __('Group') }}</span>
                                      </label>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-lg-4 groupPersons">
                                  <div class="form-group">
                                    <label>{{ __('Max Person') . '*' }}</label>
                                    <input type="number" class="form-control personInput" name="person"
                                      placeholder="{{ __('Enter person number') }}">
                                  </div>
                                </div>


                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>Particulier / enkel zakelijk</label>
                                    @php
                                      $adType = old('ad_type', 0);
                                    @endphp
                                    <div class="selectgroup w-100">
                                      <label class="selectgroup-item">
                                        <input type="radio" name="ad_type" value="0" class="selectgroup-input"
                                          {{ (int) $adType === 0 ? 'checked' : '' }}>
                                        <span class="selectgroup-button">ALLE</span>
                                      </label>

                                      <label class="selectgroup-item">
                                        <input type="radio" name="ad_type" value="1" class="selectgroup-input"
                                          {{ (int) $adType === 1 ? 'checked' : '' }}>
                                        <span class="selectgroup-button">B2B</span>
                                      </label>
                                    </div>
                                  </div>
                                </div>


                                @if ($current_package != '[]')
                                  @if ($current_package->zoom_meeting_status == 1)
                                    <div class="col-lg-4">
                                      <div class="form-group">
                                        <label>{{ __('Zoom') }}</label>
                                        <div class="selectgroup w-100">
                                          <label class="selectgroup-item">
                                            <input type="radio" name="zoom_meeting" value="1"
                                              class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                          </label>

                                          <label class="selectgroup-item">
                                            <input type="radio" name="zoom_meeting" value="0" class="selectgroup-input"
                                              checked="">
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                          </label>
                                        </div>
                                        <p>
                                          <small
                                            class="text-warning">{{ __('If you enable zoom, then you have to set your zoom credentials.') }}
                                          </small>
                                          <a target="_blank" class="link-primary " href="{{ route('vendor.plugins.index') }}">
                                            {{ __('Click to proceed') }}
                                          </a>
                                        </p>
                                      </div>
                                    </div>
                                  @endif
                                  @if ($current_package->calendar_status == 1)
                                    <div class="col-lg-4">
                                      <div class="form-group">
                                        <label>{{ __('Google Calendar') }}</label>
                                        <div class="selectgroup w-100">
                                          <label class="selectgroup-item">
                                            <input type="radio" name="calender_status" value="1"
                                              class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                          </label>

                                          <label class="selectgroup-item">
                                            <input type="radio" name="calender_status" value="0"
                                              class="selectgroup-input" checked="">
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                          </label>
                                        </div>
                                        <p>
                                          <small class="text-warning">
                                            {{ __('If you enable calendar, then you have to set your calendar credentials') }}
                                          </small>
                                          <a target="_blank" class="link-primary" href="{{ route('vendor.plugins.index') }}">
                                            {{ __('Click to proceed') }}
                                          </a>
                                        </p>
                                      </div>
                                    </div>
                                  @endif
                                @endif
                              </div>

                        </div>

                      </div>

                    </div>


                    <br>
                    <ul class="nav nav-tabs" id="languageTabs" role="tablist">

                    <?php
                    $languages = $languages->sortByDesc(function ($lang) {
                        return $lang->is_default;           // default = 1 comes first (descending)
                    })->values();
                    ?>

                        @foreach ($languages as $index => $language)
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link {{ $language->is_default == 1 ? 'active' : '' }}"
                                    id="tab-{{ $language->id }}-tab"
                                    data-toggle="tab"
                                    data-target="#tab-{{ $language->id }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab-{{ $language->id }}"
                                    aria-selected="{{ $language->is_default == 1 ? 'true' : 'false' }}">
                                    {{ $language->name }} {{ __('Language') }}
                                    {{ $language->is_default == 1 ? '(Default)' : '' }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content mt-4" id="languageTabContent">
                        @foreach ($languages as $language)
                            <div
                                class="tab-pane fade {{ $language->is_default == 1 ? 'show active' : '' }}"
                                id="tab-{{ $language->id }}"
                                role="tabpanel"
                                aria-labelledby="tab-{{ $language->id }}-tab">

                                <div class="language-form {{ $language->direction == 1 ? 'rtl text-right' : '' }}">

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Title') }}*</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old($language->code . '_name') }}"
                                                    name="{{ $language->code }}_name"
                                                    placeholder="{{ __('Enter Service Title') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            @php
                                                $categories = App\Models\Services\ServiceCategory::where('language_id', $language->id)
                                                    ->where('status', 1)
                                                    ->get();

                                                $subcategories = App\Models\Services\ServiceSubCategory::where('language_id', $language->id)
                                                    ->where('status', 1)
                                                    ->get();

                                                $categoryNameById = $categories->pluck('name', 'id');
                                                $subcategoryNameById = $subcategories->pluck('name', 'id');

                                                $oldCategoryId = old($language->code . '_category_id');
                                                $oldSubcategoryId = old($language->code . '_subcategory_id');
                                                // Enforce selecting a subcategory (category-only not allowed)
                                                if (empty($oldSubcategoryId)) {
                                                    $oldCategoryId = null;
                                                    $oldSubcategoryId = null;
                                                }
                                                $oldCategoryName = !empty($oldCategoryId) ? ($categoryNameById[$oldCategoryId] ?? null) : null;
                                                $oldSubcategoryName = !empty($oldSubcategoryId) ? ($subcategoryNameById[$oldSubcategoryId] ?? null) : null;
                                                $categorySearchValue = $oldSubcategoryName && $oldCategoryName
                                                    ? ($oldCategoryName . ' › ' . $oldSubcategoryName)
                                                    : '';

                                                // Build search dataset for autocomplete (subcategory combos only)
                                                $categorySearchItems = [];
                                                foreach ($subcategories as $subcategory) {
                                                    $catName = $categoryNameById[$subcategory->category_id] ?? null;
                                                    if (empty($catName)) {
                                                        continue;
                                                    }
                                                    $categorySearchItems[] = [
                                                        'label' => $catName . ' › ' . $subcategory->name,
                                                        'categoryId' => $subcategory->category_id,
                                                        'subcategoryId' => $subcategory->id,
                                                    ];
                                                }
                                            @endphp

                                            <div class="form-group position-relative service-cat-search-group">
                                                <label>{{ __('Category / Subcategory') }}*</label>
                                                <input type="text" class="form-control service-cat-search"
                                                    data-lang_code="{{ $language->code }}"
                                                    value="{{ $categorySearchValue }}"
                                                    placeholder="{{ __('Type to search...') }}" autocomplete="off">
                                                <input type="hidden" name="{{ $language->code }}_category_id"
                                                    value="{{ $oldCategoryId }}">
                                                <input type="hidden" name="{{ $language->code }}_subcategory_id"
                                                    value="{{ $oldSubcategoryId }}">
                                                <div class="list-group service-cat-search-results d-none"
                                                    data-lang_code="{{ $language->code }}" role="listbox" aria-label="{{ __('Search results') }}"></div>
                                                <script type="application/json"
                                                    id="service-cat-search-data-{{ $language->code }}">{!! json_encode($categorySearchItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
                                                <small class="text-muted service-cat-search-help">{{ __('Select a subcategory (Category › Subcategory)') }}</small>
                                                <p id="err_{{ $language->code }}_category_id" class="mt-1 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ __('Address') }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old($language->code . '_address') }}"
                                                    name="{{ $language->code }}_address"
                                                    placeholder="{{ __('Enter Address') }}"
                                                    id="search-address">
                                                @if ($language->is_default == 1 && $websiteInfo->google_map_status == 1)
                                                    <a href="" class="btn btn-secondary mt-2 btn-sm" data-toggle="modal"
                                                        data-target="#GoogleMapModal">
                                                        <i class="fas fa-eye"></i> {{ __('Show Map') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-4 {{ $language->is_default == 1 ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <label>{{ __('Latitude') }}*</label>
                                                <input type="number" class="form-control latitude"
                                                    value="{{ old('latitude') }}" name="latitude"
                                                    placeholder="{{ __('Enter Latitude') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 {{ $language->is_default == 1 ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <label>{{ __('Longitude') }}*</label>
                                                <input type="number" class="form-control longitude"
                                                    value="{{ old('longitude') }}" name="longitude"
                                                    placeholder="{{ __('Enter Longitude') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                <label>{{ __('Features') }}</label>
                                                <textarea name="{{ $language->code }}_features" class="form-control"></textarea>
                                                <p class="text-warning">
                                                    {{ __('Each new line will be shown as a new feature in this service') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                <label>{{ __('Description') }} *</label>
                                                <textarea id="{{ $language->code }}_description"
                                                    class="form-control summernote"
                                                    name="{{ $language->code }}_description"
                                                    data-height="300"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                <label>{{ __('Meta Keywords') }}</label>
                                                <input class="form-control"
                                                    name="{{ $language->code }}_meta_keyword"
                                                    placeholder="{{ __('Enter Meta Keywords') }}"
                                                    data-role="tagsinput">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                <label>{{ __('Meta Description') }}</label>
                                                <textarea class="form-control"
                                                    name="{{ $language->code }}_meta_description"
                                                    rows="5"
                                                    placeholder="{{ __('Enter Meta Description') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Clone section -->
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            @php $currLang = $language; @endphp
                                            @foreach ($languages as $otherLanguage)
                                                @continue($otherLanguage->id == $currLang->id)
                                                <div class="form-check py-0">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            onchange="cloneInput('tab-{{ $currLang->id }}', 'tab-{{ $otherLanguage->id }}', event)">
                                                        <span class="form-check-sign">
                                                            {{ __('Clone for') }}
                                                            <strong class="text-capitalize text-secondary">{{ $otherLanguage->name }}</strong>
                                                            {{ __('language') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                  </div>
                </form>
              </div>



            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" id="ServiceSubmit" class="btn btn-success">
                    {{ __('Save') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if ($websiteInfo->google_map_status == 1)
    @includeIf('map.map-modal');
  @endif
@endsection
@section('script')
  @if ($websiteInfo->google_map_status == 1)
    <script
      src="https://maps.googleapis.com/maps/api/js?key={{ $websiteInfo->google_map_api_key }}&libraries=places&callback=initMap"
      async defer></script>
    <script src="{{ asset('assets/js/map-init.js') }}"></script>
  @endif
  <style>
    .service-cat-search-results {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 1050;
      max-height: 260px;
      overflow: auto;
      margin-top: 2px;
      border-radius: 10px;
      padding: 6px;
      border: 1px solid rgba(0, 0, 0, 0.12);
      background: #fff;
      box-shadow: 0 14px 34px rgba(0, 0, 0, 0.18);
    }

    .service-cat-search-group.is-open .service-cat-search-help {
      display: none; /* remove the gap between input and dropdown */
    }

    .service-cat-search-results .list-group-item {
      border: 0;
      border-radius: 8px;
      padding: 10px 12px;
      background: transparent;
      color: inherit;
      text-align: left;
    }

    .service-cat-search-results .list-group-item:hover,
    .service-cat-search-results .list-group-item.is-active {
      background: rgba(0, 0, 0, 0.06);
    }

    [dir="rtl"] .service-cat-search-results .list-group-item {
      text-align: right;
    }

    body[data-background-color="dark"] .service-cat-search-results {
      background: #0b1220;
      border-color: rgba(255, 255, 255, 0.12);
      box-shadow: 0 14px 34px rgba(0, 0, 0, 0.55);
      color: rgba(255, 255, 255, 0.92);
    }

    body[data-background-color="dark"] .service-cat-search-results .list-group-item:hover,
    body[data-background-color="dark"] .service-cat-search-results .list-group-item.is-active {
      background: rgba(255, 255, 255, 0.08);
    }
  </style>
  <script>
    'use strict';
    var storeUrl = "{{ route('vendor.service.imagesstore') }}";
    var removeUrl = "{{ route('vendor.service.imagermv') }}";
    var sliderDelete = "{{ route('vendor.service.slider.delete') }}";
    let galleryImages = "{{ $sliderImage }}";
    let authUser = 'vendor';
  </script>
  <script src="{{ asset('assets/js/vendor-dropzone.js') }}"></script>
  <script src="{{ asset('assets/js/services.js') }}"></script>
  <script>
    (function() {
      'use strict';
      const defaultLangCode = "{{ $defaultLang->code }}";
      const requiredSubcategoryText = "{{ __('Please select a subcategory from the list.') }}";

      function normalize(str) {
        return (str || '').toString().toLowerCase().trim();
      }

      function setError(langCode, message) {
        const $input = $(`.service-cat-search[data-lang_code="${langCode}"]`);
        const $err = $(`#err_${langCode}_category_id`);
        $input.addClass('is-invalid');
        $err.text(message || requiredSubcategoryText);
      }

      function clearError(langCode) {
        const $input = $(`.service-cat-search[data-lang_code="${langCode}"]`);
        const $err = $(`#err_${langCode}_category_id`);
        $input.removeClass('is-invalid');
        $err.text('');
      }

      function getSearchItems(langCode) {
        const el = document.getElementById(`service-cat-search-data-${langCode}`);
        if (!el) return [];
        try {
          return JSON.parse(el.textContent || '[]') || [];
        } catch (_e) {
          return [];
        }
      }

      function hideResults($results) {
        $results.closest('.service-cat-search-group').removeClass('is-open');
        $results.addClass('d-none').empty();
      }

      function showResults($results) {
        $results.closest('.service-cat-search-group').addClass('is-open');
        $results.removeClass('d-none');
      }

      function setHiddenCategory(langCode, categoryId, subcategoryId) {
        $(`input[name="${langCode}_category_id"]`).val(categoryId ? String(categoryId) : '');
        $(`input[name="${langCode}_subcategory_id"]`).val(subcategoryId ? String(subcategoryId) : '');
      }

      function clearHiddenCategory(langCode) {
        setHiddenCategory(langCode, '', '');
      }

      function setActive($results, nextIndex) {
        const $items = $results.find('.service-cat-search-item');
        if (!$items.length) return;

        const max = $items.length - 1;
        let idx = Math.max(0, Math.min(max, nextIndex));
        $results.data('activeIndex', idx);

        $items.removeClass('is-active').attr('aria-selected', 'false');
        const $active = $items.eq(idx);
        $active.addClass('is-active').attr('aria-selected', 'true');

        // ensure visible
        const el = $active.get(0);
        if (el && typeof el.scrollIntoView === 'function') {
          el.scrollIntoView({ block: 'nearest' });
        }
      }

      function selectItem($results, $item) {
        const langCode = $results.data('lang_code');
        const catId = $item.data('cat-id');
        const subId = $item.data('sub-id') || null;
        const label = $item.text();

        const $input = $(`.service-cat-search[data-lang_code="${langCode}"]`);
        $input.val(label);
        hideResults($results);
        setHiddenCategory(langCode, catId, subId);
        clearError(langCode);
      }

      $(document).on('input', '.service-cat-search', function() {
        const $input = $(this);
        const langCode = $input.data('lang_code');
        const term = normalize($input.val());
        const $results = $(`.service-cat-search-results[data-lang_code="${langCode}"]`);

        clearHiddenCategory(langCode);
        clearError(langCode);

        if (!term || term.length < 2) {
          hideResults($results);
          return;
        }

        const items = getSearchItems(langCode);
        const matches = items
          .filter((item) => normalize(item.label).includes(term))
          .slice(0, 10);

        if (!matches.length) {
          hideResults($results);
          return;
        }

        const markup = matches
          .map((item, idx) => {
            const catId = item.categoryId;
            const subId = item.subcategoryId;
            const label = item.label;
            const safeLabel = $('<div>').text(label).html();
            return `<button type="button" role="option" aria-selected="false"
                class="list-group-item list-group-item-action service-cat-search-item"
                data-index="${idx}" data-cat-id="${catId}" data-sub-id="${subId || ''}">${safeLabel}</button>`;
          })
          .join('');

        $results.html(markup);
        showResults($results);
        setActive($results, 0);
      });

      $(document).on('mouseenter', '.service-cat-search-results .service-cat-search-item', function() {
        const $item = $(this);
        const $results = $item.closest('.service-cat-search-results');
        const idx = parseInt($item.data('index'), 10);
        if (!Number.isNaN(idx)) setActive($results, idx);
      });

      $(document).on('click', '.service-cat-search-results .service-cat-search-item', function() {
        const $item = $(this);
        const $results = $item.closest('.service-cat-search-results');
        selectItem($results, $item);
      });

      $(document).on('keydown', '.service-cat-search', function(e) {
        const $input = $(this);
        const langCode = $input.data('lang_code');
        const $results = $(`.service-cat-search-results[data-lang_code="${langCode}"]`);

        // only handle when dropdown is open / has items
        const $items = $results.find('.service-cat-search-item');
        const isOpen = !$results.hasClass('d-none') && $items.length > 0;

        if (e.key === 'Escape') {
          hideResults($results);
          return;
        }

        if (!isOpen) return;

        if (e.key === 'ArrowDown' || e.key === 'Down') {
          e.preventDefault();
          const current = parseInt($results.data('activeIndex') || 0, 10);
          setActive($results, current + 1);
          return;
        }

        if (e.key === 'ArrowUp' || e.key === 'Up') {
          e.preventDefault();
          const current = parseInt($results.data('activeIndex') || 0, 10);
          setActive($results, current - 1);
          return;
        }

        if (e.key === 'Enter') {
          e.preventDefault();
          const current = parseInt($results.data('activeIndex') || 0, 10);
          const $active = $items.eq(current);
          if ($active.length) selectItem($results, $active);
          return;
        }
      });

      function validateRequiredSelection() {
        const catId = $(`input[name="${defaultLangCode}_category_id"]`).val();
        const subId = $(`input[name="${defaultLangCode}_subcategory_id"]`).val();
        if (!catId || !subId) {
          setError(defaultLangCode);
          return false;
        }
        return true;
      }

      // Run validation BEFORE services.js click handler (capture phase)
      document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('ServiceSubmit');
        if (!btn) return;
        btn.addEventListener('click', function(e) {
          if (validateRequiredSelection()) return;
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
        }, true);
      });

      // Close dropdown on outside click / ESC
      $(document).on('click', function(e) {
        if ($(e.target).closest('.service-cat-search, .service-cat-search-results').length) return;
        $('.service-cat-search-results').each(function() {
          hideResults($(this));
        });
      });

      $(document).on('keydown', function(e) {
        if (e.key !== 'Escape') return;
        $('.service-cat-search-results').each(function() {
          hideResults($(this));
        });
      });
    })();
  </script>
@endsection
