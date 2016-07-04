<?php

/**
 * Adds Open Graph metadata to Pages.
 *
 * @namespace silverstripe-seo
 * @package open-graph
 * @author Andrew Gerber <atari@graphiquesdigitale.net>
 * @version 1.0.0
 *
 */

class SEO_OpenGraph_SiteTree_DataExtension extends DataExtension {


	/* Static Variables
	------------------------------------------------------------------------------*/

	//
	private static $SEOOpenGraphUpload = 'SEO/OpenGraph/';


	/* Overload Model
	------------------------------------------------------------------------------*/

	private static $db = array(
		'OpenGraphData' => 'Text'
	);
	private static $has_one = array(
		'OpenGraphImage' => 'Image',
	);

	//
//	private static $defaults = array(
//		'OpenGraphData' => array(
//			'og:type' => 'website',
//			'og:url' => null,
//			'og:site_name' => null,
//			'og:title' => null,
//			'og:description' => null,
//			'og:image' => null
//		)
//	);


	/* Variables
	------------------------------------------------------------------------------*/

	//
	protected static $types = array(
		'website' => 'Website (default)',
		'off' => 'Disabled for this page'
// 		'article' => 'Article',
//		'book' => 'Book',
//		'profile' => 'Profile',
	);

	//
	protected static $OpenGraphProtocol = array(
		'og:type' => 'website',
		'og:url' => null,
		'og:site_name' => null,
		'og:title' => null,
		'og:description' => null,
		'og:image' => null
	);


	/* Overload Methods
	------------------------------------------------------------------------------*/

	// CMS Fields
	public function updateCMSFields(FieldList $fields) {

		// vars
		$config = SiteConfig::current_site_config();
		$owner = $this->owner;

		// decode data into array
		$data = json_decode($owner->OpenGraphData, true);

		// @todo Add repair method if data is missing / corrupt ~ for fringe cases

		// tab
		$tab = new Tab('OpenGraph');

		// add disabled/error state if `off`
		if ($data['og:type'] === 'off') {
			$tab->addExtraClass('error');
		}

		// add the tab
		$fields->addFieldToTab('Root.Metadata', $tab, 'FullOutput');

		// new identity
		$tab = 'Root.Metadata.OpenGraph';

		// add description
		// type always visible
		$fields->addFieldsToTab($tab, array(
			// header
			LabelField::create('OpenGraphHeader', '@todo Information</a>')
				->addExtraClass('information'),
			// type
			DropdownField::create('OpenGraphType', '<a href="http://ogp.me/#types">og:type</a>', self::$types, $data['og:type']),
		));

		if ($data['og:type'] !== 'off') {
			$fields->addFieldsToTab($tab, array(
				// url
				ReadonlyField::create('OpenGraphURL', 'Canonical URL', $owner->AbsoluteLink()),
				// site name
				TextField::create('OpenGraphSiteName', 'Site Name', $data['og:site_name'])
					->setAttribute('placeholder', $config->Title),
				// title
				TextField::create('OpenGraphTitle', 'Page Title', $data['og:title'])
					->setAttribute('placeholder', $owner->Title),
				// description
				TextareaField::create('OpenGraphDescription', 'Description', $data['og:description'])
					->setAttribute('placeholder', $owner->GenerateDescription()),
				// image
				UploadField::create('OpenGraphImage', 'Image<pre>type: png/jpg/gif</pre><pre>size: variable *</pre>', $owner->OpenGraphImage)
					->setAllowedExtensions(array('png', 'jpg', 'jpeg', 'gif'))
					->setFolderName(self::$SEOOpenGraphUpload . $owner->Title)
					->setDescription('* <a href="https://developers.facebook.com/docs/sharing/best-practices#images" target="_blank">Facebook image best practices</a>, or use any preferred Open Graph guide.'),
			));
		}

	}

	// Require Default Records
 	public function requireDefaultRecords() {

		// init parent
		parent::requireDefaultRecords();

 		//
 		$pages = SiteTree::get();
 		if ($pages->count() > 0) {
 			$count = 0;
 			foreach ($pages as $page) {
				if ($page->OpenGraphData == null) {
					$page->OpenGraphData = json_encode(self::$OpenGraphProtocol);
					$page->write();
					$count++;
				}
 			}
			if ($count > 0) {
				DB::alteration_message('Open Graph Data added to ' . $count . ' page(s)', 'created');
			}
 		}

 	}

	//
	public function onBeforeWrite() {

		// init parent
		parent::onBeforeWrite();

		// owner
		$owner = $this->owner;

		// this will NOT trigger during requireDefaultRecords(), to prevent setting blank values
		// @todo Is this the optimal solution ??
		if ($owner->OpenGraphType != null) {

			//
			$data = json_decode($owner->OpenGraphData, true);

			//
			$data['og:type'] = $owner->OpenGraphType;

			// prevent clearing of existing values
			if ($data['og:type'] !== 'off') {
				// URL
				$data['og:url'] = $owner->OpenGraphURL;
				// site name
				$data['og:site_name'] = $owner->OpenGraphSiteName;
				// title
				$data['og:title'] = $owner->OpenGraphTitle;
				// description
				$data['og:description'] = $owner->OpenGraphDescription;
				// image is set normally
//				$data['og:image'] = $owner->OpenGraphImage;
			}

			//
			$owner->OpenGraphData = json_encode($data);

		}

	}


	/* Template Methods
	------------------------------------------------------------------------------*/

	/**
	 * @name updateMetadata
	 *
	 * Updates metadata with icons.
	 *
	 * @param SiteConfig $config
	 * @param SiteTree $owner
	 * @param $metadata
	 * @return void
	 */
	public function updateMetadata(SiteConfig $config, SiteTree $owner, &$metadata) {

		// decode into array
		$data = json_decode($owner->OpenGraphData, true);

		// check for data or throw an error
		if ($data) {

			// check if off
			if ($data['og:type'] !== 'off') {

				// Header
				$metadata .= $owner->MarkupComment('Open Graph');

				// Type
				$metadata .= $owner->MarkupOpenGraph('og:type', $data['og:type']);

				// URL
				$metadata .= $owner->MarkupOpenGraph('og:url', $owner->AbsoluteLink());

				// Site Name
				$siteName = ($data['og:site_name']) ? $data['og:site_name'] : $config->Title;
				$metadata .= $owner->MarkupOpenGraph('og:site_name', $siteName, true);

				// Title
				$title = ($data['og:title']) ? $data['og:title'] : $owner->Title;
				$metadata .= $owner->MarkupOpenGraph('og:title', $title, true);

				// Description
				$description = ($data['og:description']) ? $data['og:description'] : $owner->GenerateDescription();
				$metadata .= $owner->MarkupOpenGraph('og:description', $description, true);

				// Image
				if ($owner->OpenGraphImage()->exists()) {
					$metadata .= $owner->MarkupOpenGraph('og:image', $owner->OpenGraphImage()->getAbsoluteURL());
				}

			} else {

				// OFF
				$metadata .= $owner->MarkupComment('Open Graph [ off ]');

			}

		} else {

			// ERROR
			$metadata .= $owner->MarkupComment('Open Graph [ error ]');

		}

	}


	/* Class Methods
	------------------------------------------------------------------------------*/

	/**
	 * Returns markup for an Open Graph meta element.
	 *
	 * @var $property
	 * @var $content
	 * @var $encode
	 *
	 * @return string
	 */
	public function MarkupOpenGraph($property, $content, $encode = false) {
		// encode content
		if ($encode) $content = htmlentities($content, ENT_QUOTES, $this->owner->Charset);
		// format & return
		return '<meta property="' . $property . '" content="' . $content . '" />' . PHP_EOL;
	}

}
