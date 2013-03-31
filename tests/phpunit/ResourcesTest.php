<?php

namespace SRF\Test;

use ResourceLoader;
use ResourceLoaderModule;
use ResourceLoaderContext;

/**
 * Tests for resource definitions and files
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
 * @file
 * @since 1.9
 *
 * @ingroup SRF
 * @ingroup Test
 *
 * @group SRFExtension
 * @group SMWExtension
 *
 * @licence GNU GPL v2+
 * @author mwjames
 */
class ResourcesTest extends \MediaWikiTestCase {

	/**
	 * Helper method to load resources only valid for this extension
	 *
	 * @return array
	 */
	private function getSRFResourceModules(){
		global $srfgIP;
		return include $srfgIP . '/' . 'Resources.php';
	}

	/**
	 * DataProvider
	 *
	 */
	public function moduleDataProvider() {
		$resourceLoader = new ResourceLoader();
		$context = ResourceLoaderContext::newDummyContext();
		$modules = $this->getSRFResourceModules();

		return array( array( $modules, $resourceLoader, $context ) );
	}

	/**
	 * Test scripts accessibility
	 *
	 * @dataProvider moduleDataProvider
	 */
	public function testModulesScriptsFilesAreAccessible( $modules, ResourceLoader $resourceLoader, $context ){

		foreach ( $modules as $name => $values ){

			// Get module details
			$module = $resourceLoader->getModule( $name );

			// Get scripts per module
			$scripts = $module->getScript( $context );
			$this->assertInternalType( 'string', $scripts );
		}
	}

	/**
	 * Test styles accessibility
	 *
	 * @dataProvider moduleDataProvider
	 */
	public function testModulesStylesFilesAreAccessible( $modules, ResourceLoader $resourceLoader, $context  ){

		foreach ( $modules as $name => $values ){

			// Get module details
			$module = $resourceLoader->getModule( $name );

			// Get styles per module
			$styles = $module->getStyles( $context );
			$this->assertContainsOnly( 'string', $styles );
		}
	}
}