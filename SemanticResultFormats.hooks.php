<?php

/**
 * Static class for hooks handled by the Semantic Result Formats.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.7
 *
 * @file
 * @ingroup SemanticResultFormats
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author mwjames
 */
final class SRFHooks {

	/**
	 * Hook to add PHPUnit test cases.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
	 *
	 * @since 1.8
	 *
	 * @param array $files
	 *
	 * @return boolean
	 */
	public static function registerUnitTests( array &$files ) {
		// Keep this in alphabetical order please!
		$testFiles = array(
			'formats/Array',
			'formats/Dygraphs',
			'formats/EventCalendar',
			'formats/Gallery',
			'formats/Graph',
			'formats/Incoming',
			'formats/jqPlotChart',
			'formats/jqPlotSeries',
			'formats/ListWidget',
			'formats/Math',
			'formats/PageWidget',
			'formats/Sparkline',
			'formats/TagCloud',
			'formats/Timeseries',
			'formats/vCard',
			'formats/MediaPlayer',
			'formats/DataTables',

			// Boilerplate
			// Register your testclass
			// 'formats/Boilerplate',
		);

		foreach ( $testFiles as $file ) {
			$files[] = dirname( __FILE__ ) . '/tests/phpunit/' . $file . 'Test.php';
		}

		return true;
	}

	/**
	 * Add new JavaScript/QUnit testing modules
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderTestModules
	 *
	 * @since: 1.9
	 *
	 * @param  array $testModules array of JavaScript testing modules
	 * @param  ResourceLoader $resourceLoader object
	 *
	 * @return boolean
	 */
	public static function registerQUnitTests( array &$testModules, ResourceLoader &$resourceLoader ) {
		$testModules['qunit']['ext.srf.tests'] = array(
			'scripts' => array(
				// Base
				'tests/qunit/ext.srf.test.js',
				'tests/qunit/ext.srf.util.test.js',

				// Formats
				'tests/qunit/formats/ext.srf.formats.eventcalendar.tests.js',
				'tests/qunit/formats/ext.srf.formats.datatables.test.js',

				// Widgets
				'tests/qunit/widgets/ext.srf.widgets.eventcalendar.tests.js',
				'tests/qunit/widgets/ext.srf.widgets.optionslist.test.js',
				'tests/qunit/widgets/ext.srf.widgets.panel.test.js',
				'tests/qunit/widgets/ext.srf.widgets.parameters.test.js'

			),
			'dependencies' => array(
				'ext.srf',
				'ext.srf.util',
				'ext.srf.eventcalendar',
				'ext.srf.datatables',
				'ext.srf.widgets'
			),
			'position' => 'top',
			'localBasePath' => __DIR__,
			'remoteExtPath' => 'SemanticResultFormats',
		);

		return true;
	}

	/**
	 * Adds a link to Admin Links page.
	 *
	 * @since 1.7
	 *
	 * @param ALTree $admin_links_tree
	 *
	 * @return boolean
	 */
	public static function addToAdminLinks( ALTree &$admin_links_tree ) {
		$displaying_data_section = $admin_links_tree->getSection( wfMessage( 'smw_adminlinks_displayingdata' )->text() );

		// Escape is SMW hasn't added links.
		if ( is_null( $displaying_data_section ) ) {
			return true;
		}

		$smw_docu_row = $displaying_data_section->getRow( 'smw' );
		$srf_docu_label = wfMessage( 'adminlinks_documentation', wfMessage( 'srf-name' )->text() )->text();
		$smw_docu_row->addItem( AlItem::newFromExternalLink( 'https://www.mediawiki.org/wiki/Extension:Semantic_Result_Formats', $srf_docu_label ) );

		return true;
	}

	/**
	 * Hook: ResourceLoaderGetConfigVars called right before
	 * ResourceLoaderStartUpModule::getConfig returns
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderGetConfigVars
	 *
	 * @param &$vars Array of variables to be added into the output of the startup module.
	 *
	 * @return true
	 */
	public static function onResourceLoaderGetConfigVars( &$vars ) {

		$vars['srf'] = array(
			'version' => SRF_VERSION,
			'options' => array(
				'thumbsize' => $GLOBALS['wgThumbLimits']
			)
		);

		return true;
	}

	/**
	 * Hook: GetPreferences adds user preference
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetPreferences
	 *
	 * @param User $user
	 * @param array $preferences
	 *
	 * @return true
	 */
	public static function onGetPreferences( $user, &$preferences ) {

		// Intro text, do not escape the message here as it contains
		// href links
		$preferences['srf-prefs-intro'] =
			array(
				'type' => 'info',
				'label' => '&#160;',
				'default' => Html::rawElement(
					'span',
					array( 'class' => 'srf-prefs-intro' ),
					wfMessage( 'srf-prefs-intro-text' )->parseAsBlock()
				),
				'section' => 'smw/srf',
				'raw' => 1,
				'rawrow' => 1,
			);

		// Enable auto update during a page refresh
		$preferences['srf-prefs-eventcalendar-options-update-default'] = array(
			'type' => 'toggle',
			'label-message' => 'srf-prefs-eventcalendar-options-update-default',
			'section' => 'smw/srf-eventcalendar-options',
		);

		// Enable paneView by default
		$preferences['srf-prefs-eventcalendar-options-paneview-default'] = array(
			'type' => 'toggle',
			'label-message' => 'srf-prefs-eventcalendar-options-paneview-default',
			'section' => 'smw/srf-eventcalendar-options',
		);


		// Enable auto update during a page refresh
		$preferences['srf-prefs-datatables-options-update-default'] = array(
			'type' => 'toggle',
			'label-message' => 'srf-prefs-datatables-options-update-default',
			'section' => 'smw/srf-datatables-options',
		);

		// Enable local caching
		$preferences['srf-prefs-datatables-options-cache-default'] = array(
			'type' => 'toggle',
			'label-message' => 'srf-prefs-datatables-options-cache-default',
			'section' => 'smw/srf-datatables-options',
		);

		return true;
	}
}