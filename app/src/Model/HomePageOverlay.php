<?php

namespace App\Model;

use App\Extension\CallToAction;
use App\Form\Field\DBVideoURL;
use App\Page\HomePage;
use App\Traits\CMSPermissionProvider;
use Cake\Chronos\Chronos;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\SelectionGroup;
use SilverStripe\Forms\SelectionGroup_Item;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\Queries\SQLUpdate;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class HomePageOverlay extends DataObject
{
    use CMSPermissionProvider;

    const INACTIVE = 0;
    const PENDING = 1;
    const ACTIVE = 2;

    protected $status_types = [
        0 => 'Inactive',
        1 => 'Pending',
        2 => 'Active'
    ];

    private static $table_name = 'HomePageOverlay';
    private static $default_sort = ['Status DESC', 'EndDate'];

    private static $extensions = [
        CallToAction::class
    ];

    private static $db = [
        'Status' => 'Int',
        'Enabled' => 'Boolean',
        'Title' => 'Varchar(50)',
        'Content' => 'HTMLText',
        'IncludeContent' => 'Boolean',
        'IncludeImage' => 'Boolean',
        'IncludeVideo' => 'Boolean',
        'IncludeImageLink' => 'Boolean',
        'ImageLinkType' => 'Enum("Internal,File, External", "Internal")',
        'ImageExternalLink' => 'Varchar',
        'VideoURL' => DBVideoURL::class,
        'StartDate' => 'Date',
        'EndDate' => 'Date'
    ];

    private static $has_one = [
        'Image' => Image::class,
        'ImageInternalLink' => SiteTree::class,
        'ImageFile' => File::class,
        'HomePage' => HomePage::class
    ];

    private static $owns = [
        'Image'
    ];

    private static $summary_fields = [
        'Title' => 'Title',
        'StartDate.Nice' => 'Start Date',
        'EndDate.Nice' => 'End Date',
        'NiceStatus' => 'Status'
    ];

    private static $searchable_fields = [
        'Title'
    ];

    public function populateDefaults()
    {
        $this->Enabled = true;

        parent::populateDefaults();
    }

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TabSet::create('Root')
        );

        $fields->addFieldsToTab('Root.Main', [
            FieldGroup::create(
                'Enabled',
                CheckboxField::create('Enabled', 'Allow overlay to display on Home Page')
            ),
            TextField::create('Title')->setDescription('For internal use only'),
            FieldGroup::create(
                'Active Dates',
                DateField::create('StartDate')->addExtraClass('date-range--start'),
                DateField::create('EndDate')->addExtraClass('date-range--end')
            )->addExtraClass('is-date-range'),
            HeaderField::create('VideoHeader', 'Video'),
            FieldGroup::create(
                'Display Video',
                CheckboxField::create('IncludeVideo', 'Include a video in the overlay')
            ),
            Wrapper::create(
                TextField::create('VideoURL', 'Video URL')
                    ->showYouTubeHelper()
            )->displayIf('IncludeVideo')->isChecked()->end(),
            HeaderField::create('ImageHeader', 'Image'),
            FieldGroup::create(
                'Display Image',
                CheckboxField::create('IncludeImage', 'Include an image in the overlay')
            ),
            Wrapper::create(
                UploadField::create('Image')
                    ->setAllowedFileCategories('image')
                    ->setFolderName('HomePageOverlay')
            )->displayIf('IncludeImage')->isChecked()->end(),
            Wrapper::create(
                FieldGroup::create(
                    'Link Image',
                    CheckboxField::create('IncludeImageLink', 'Add a link to the image')
                )
            )->displayIf('IncludeImage')->isChecked()->end(),
            Wrapper::create(
                SelectionGroup::create(
                    'ImageLinkType',
                    [
                        SelectionGroup_Item::create(
                            'Internal',
                            [
                                TreeDropdownField::create('ImageInternalLinkID', '', SiteTree::class)->setHasEmptyDefault(true)
                            ],
                            'Link to internal page'
                        ),
                        SelectionGroup_Item::create(
                            'External',
                            TextField::create('ImageExternalLink', '')->setAttribute('placeholder', 'Enter URL'),
                            'Link to external page'
                        ),
                        SelectionGroup_Item::create(
                            'File',
                            UploadField::create('ImageFile', '')->setAllowedFileCategories('document')->setFolderName('Documents'),
                            'Link to file'
                        )
                    ]
                )->setTitle('Image Link Type')
            )->displayIf('IncludeImageLink')->isChecked()->end(),
            HeaderField::create('ContentHeader', 'Content'),
            FieldGroup::create(
                'Display Content',
                CheckboxField::create('IncludeContent', 'Include content in the overlay')
            ),
            HTMLEditorField::create('Content')->displayIf('IncludeContent')->isChecked()->end(),
            Wrapper::create(
                $this->buildCTAGrid()
            )->displayIf('IncludeContent')->isChecked()->end()
        ]);

        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    public function getCMSValidator()
    {
        return RequiredFields::create([
            'Title',
            'StartDate',
            'EndDate'
        ]);
    }

    public function validate()
    {
        $result = parent::validate();

        if (!$this->IncludeVideo && !$this->IncludeImage && !$this->IncludeContent) {
            $result->addError('An image, video or content is required');
        }

        if ($this->IncludeVideo && !$this->VideoURL) {
            $result->addFieldError('VideoURL', 'Video URL is required');
        }

        if ($this->IncludeImage && !$this->ImageID) {
            $result->addFieldError('Image', 'Image is required');
        }

        if ($this->IncludeContent && !$this->Content) {
            $result->addFieldError('Content', 'Content is required');
        }

        $others = self::get();

        if ($this->exists()) {
            $others = $others->exclude('ID', $this->ID);
        }

        if (!empty($this->getDateConflicts($others))) {
            $result->addError('Active Dates conflicts with other enabled overlays. Only one may be active at once.');
        }

        return $result;
    }

    public function Link()
    {
        return $this->HomePage()->Link('overlay') . '/' . $this->ID;
    }

    public function setStatus()
    {
        $now = Chronos::now();
        $startDate = Chronos::parse($this->StartDate);
        $endDate = Chronos::parse($this->EndDate);
        $enabled = $this->Enabled;
        $newStatus = self::INACTIVE;

        if (!$enabled) {
            $newStatus = self::INACTIVE;
        }

        if ($enabled && $now->lt($startDate)) {
            $newStatus = self::PENDING;
        }

        if ($enabled && $now->between($startDate, $endDate)) {
            $newStatus = self::ACTIVE;
        }

        $update = SQLUpdate::create('"HomePageOverlay"')->addWhere(['ID' => $this->ID]);
        $update->assign('"Status"', $newStatus);
        $update->execute();
    }

    public function getNiceStatus()
    {
        $style = ['gf-status'];

        if ($this->Status == 0) {
            $style[] = 'gf-status--red';
        }

        if ($this->Status == 1) {
            $style[] = 'gf-status--yellow';
        }

        if ($this->Status == 2) {
            $style[] = 'gf-status--green';
        }

        return LiteralField::create(
            'GFStatus',
            sprintf('<span class="%s">%s</span>', join(' ', $style), $this->status_types[$this->Status])
        );
    }

    public function getDateConflicts($others = [])
    {
        $conflicts = [];

        $start = Chronos::parse($this->StartDate);
        $end = Chronos::parse($this->EndDate);

        foreach ($others as $overlay) {
            $thisStart = Chronos::parse($overlay->StartDate);
            $thisEnd = Chronos::parse($overlay->EndDate);

            if ($overlay->Enabled) {
                if ($start->lt($thisEnd) && $end->gt($thisStart)) {
                    $conflicts[] = $overlay;
                }
            }
        }

        return $conflicts;
    }

    public function getIsOnlyImage()
    {
        return $this->IncludeImage && !$this->IncludeContent;
    }

    public function getIsOnlyContent()
    {
        return !$this->IncludeImage && $this->IncludeContent;
    }

    public function getContainerClass()
    {
        $classes = ['homepage-overlay-modal'];

        if ($this->getIsOnlyImage()) {
            $classes[] = 'is-image';
        } elseif ($this->getIsOnlyContent()) {
            $classes[] = 'is-content';
        } else {
            $classes[] = 'is-image-content';
        }

        if ($this->IncludeVideo) {
            $classes[] = 'has-video';
        }

        if ($this->IncludeVideo && !$this->IncludeContent) {
            $classes[] = 'only-video';
        }

        return join($classes, ' ');
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->IncludeContent) {
            $this->Content = null;
        }

        if (!$this->IncludeImage) {
            $this->ImageID = null;
        }
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();

        $this->setStatus();
    }
}
