<?php

namespace App\Models;

use App\Models\BasicSettings\AboutUs;
use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\CustomPage\PageContent;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\Banner;
use App\Models\HomePage\Methodology\WorkProcess;
use App\Models\HomePage\Testimony\Testimonial;
use App\Models\Journal\BlogCategory;
use App\Models\Journal\BlogInformation;
use App\Models\Services\ServiceCategory;
use App\Models\Services\ServiceContent;
use App\Models\Shop\ProductCategory;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ShippingCharge;
use App\Models\Staff\StaffContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'direction', 'is_default'];

    public function faq(): HasMany
    {
        return $this->hasMany(FAQ::class);
    }

    public function customPageInfo(): HasMany
    {
        return $this->hasMany(PageContent::class);
    }

    public function footerContent(): HasOne
    {
        return $this->hasOne(FooterContent::class);
    }

    public function footerQuickLink(): HasMany
    {
        return $this->hasMany(QuickLink::class);
    }

    public function announcementPopup(): HasMany
    {
        return $this->hasMany(Popup::class);
    }

    public function blogCategory(): HasMany
    {
        return $this->hasMany(BlogCategory::class);
    }

    public function blogInformation(): HasMany
    {
        return $this->hasMany(BlogInformation::class);
    }

    public function menuInfo(): HasOne
    {
        return $this->hasOne(MenuBuilder::class, 'language_id', 'id');
    }

    public function aboutSection(): BelongsTo
    {
        return $this->belongsTo(AboutUs::class);
    }

    public function workProcess(): HasMany
    {
        return $this->hasMany(WorkProcess::class, 'language_id', 'id');
    }

    public function features(): HasMany
    {
        return $this->hasMany(Features::class, 'language_id', 'id');
    }

    public function testimonial(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'language_id', 'id');
    }

    public function serviceCategory(): HasMany
    {
        return $this->hasMany(ServiceCategory::class, 'language_id', 'id');
    }

    public function aboutUsSection(): HasOne
    {
        return $this->hasOne(AboutUs::class, 'language_id', 'id');
    }

    public function featuresInfos(): HasMany
    {
        return $this->hasMany(Features::class, 'language_id', 'id');
    }

    public function shippingCharge(): HasMany
    {
        return $this->hasMany(ShippingCharge::class);
    }

    public function serviceContent(): HasMany
    {
        return $this->hasMany(ServiceContent::class, 'language_id', 'id');
    }

    public function productCategory(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function staffContent(): HasMany
    {
        return $this->hasMany(StaffContent::class);
    }

    public function productContent(): HasMany
    {
        return $this->hasMany(ProductContent::class);
    }

    public function vendorInfo(): HasOne
    {
        return $this->hasOne(VendorInfo::class);
    }

    public function banner(): HasOne
    {
        return $this->hasOne(Banner::class);
    }

    public function pageName(): HasOne
    {
        return $this->hasOne(PageHeading::class);
    }

    public function seoInfo(): HasOne
    {
        return $this->hasOne(SEO::class);
    }

    public function cookieAlertInfo(): HasOne
    {
        return $this->hasOne(CookieAlert::class);
    }
}
