<?php
/**
 * Tests the format_content method of _Beans_Compiler.
 *
 * @package Beans\Framework\Tests\Integration\API\Compiler
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Integration\API\Compiler;

use Beans\Framework\Tests\Integration\API\Compiler\Includes\Compiler_Test_Case;

require_once dirname( __DIR__ ) . '/includes/class-compiler-test-case.php';

/**
 * Class Tests_Beans_Compiler_Format_Content
 *
 * @package Beans\Framework\Tests\Integration\API\Compiler
 * @group   integration-tests
 * @group   api
 */
class Tests_Beans_Compiler_Format_Content extends Compiler_Test_Case {

	/**
	 * The Less content.
	 *
	 * @var string
	 */
	protected $less;

	/**
	 * The jQuery content.
	 *
	 * @var string
	 */
	protected $jquery;

	/**
	 * The JavaScript content.
	 *
	 * @var string
	 */
	protected $js;

	/**
	 * Set up the tests.
	 */
	public function setUp() {
		parent::setUp();

		$fixtures     = $this->mock_filesystem->getChild( 'fixtures' );
		$this->less   = $fixtures->getChild( 'variables.less' )->getContent() . $fixtures->getChild( 'test.less' )
				->getContent();
		$this->jquery = $fixtures->getChild( 'jquery.test.js' )->getContent();
		$this->js     = $fixtures->getChild( 'my-game-clock.js' )->getContent();
	}

	/**
	 * Test format_content() should return original content when the "type" is not
	 * a style or script (per the configuration).
	 */
	public function test_should_return_original_content_when_type_not_style_or_script() {
		$compiler = new \_Beans_Compiler( array(
			'type' => 'foo',
		) );

		// Run the tests.
		$this->assertSame( $this->less, $compiler->format_content( $this->less ) );
		$this->assertSame( $this->jquery, $compiler->format_content( $this->jquery ) );
		$this->assertSame( $this->js, $compiler->format_content( $this->js ) );
	}

	/**
	 * Test format_content() should return compiled CSS (not minified) from the Less combined fragments.
	 */
	public function test_should_return_compiled_css() {
		$compiler = new \_Beans_Compiler( array(
			'id'     => 'test',
			'type'   => 'style',
			'format' => 'less',
		) );

		// Turn on development mode.
		update_option( 'beans_dev_mode', true );

		$expected_css = <<<EOB
body {
  background-color: #fff;
  color: #000;
  font-size: 18px;
}
EOB;
		// Run the test.
		$this->assertSame( $expected_css, $compiler->format_content( $this->less ) );
	}

	/**
	 * Test format_content() should return minified, compiled CSS from the Less combined fragments.
	 */
	public function test_should_return_minified_compiled_css() {
		$compiler = new \_Beans_Compiler( array(
			'id'     => 'test',
			'type'   => 'style',
			'format' => 'less',
		) );

		// Turn off development mode.
		update_option( 'beans_dev_mode', false );

		// Run the test.
		$this->assertContains(
			'body{background-color:#fff;color:#000;font-size:18px;',
			$compiler->format_content( $this->less )
		);
	}

	/**
	 * Test format_content() should return the original jQuery when site is not in development mode,
	 * but "minify_js" is disabled.
	 */
	public function test_should_return_original_jquery_when_minify_js_disabled() {
		$compiler = new \_Beans_Compiler( array(
			'id'        => 'test',
			'type'      => 'script',
			'minify_js' => false,
		) );

		// Turn off development mode.
		update_option( 'beans_dev_mode', false );

		// Run the test.
		$this->assertSame( $this->jquery, $compiler->format_content( $this->jquery ) );
	}

	/**
	 * Test format_content() should return the original jQuery when "minify_js" is enabled,
	 * but the site is in development mode.
	 */
	public function test_should_always_return_original_jquery_when_in_dev_mode() {
		$compiler = new \_Beans_Compiler( array(
			'id'        => 'test',
			'type'      => 'script',
			'minify_js' => true,
		) );

		// Turn on development mode.
		update_option( 'beans_dev_mode', true );

		// Run the test.
		$this->assertSame( $this->jquery, $compiler->format_content( $this->jquery ) );
	}

	/**
	 * Test format_content() should return minified jQuery when "minify_js" is enabled
	 * and the site is not in development mode.
	 */
	public function test_should_return_minified_jquery_when_not_in_dev_mode_and_minify_js_enabled() {
		$compiler = new \_Beans_Compiler( array(
			'id'        => 'test',
			'type'      => 'script',
			'minify_js' => true,
		) );

		// Turn off development mode.
		update_option( 'beans_dev_mode', false );

		$expected = <<<EOB
(function($){'use strict';var init=function(){/$('some-button').on('click',clickHandler);}
var clickHandler=function(event){event.preventDefault();}
$(document).ready(function(){init();});})(jQuery);
EOB;
		// Run the test.
		$this->assertSame( str_replace( '/$', '$', $expected ), $compiler->format_content( $this->jquery ) );
	}

	/**
	 * Test format_content() should return the original JavaScript when site is not in development mode,
	 * but "minify_js" is disabled.
	 */
	public function test_should_return_original_js_when_minify_js_disabled() {
		$compiler = new \_Beans_Compiler( array(
			'id'        => 'test',
			'type'      => 'script',
			'minify_js' => false,
		) );

		// Turn off development mode.
		update_option( 'beans_dev_mode', false );

		// Run the test.
		$this->assertSame( $this->js, $compiler->format_content( $this->js ) );
	}

	/**
	 * Test format_content() should return the original JavaScript when "minify_js" is enabled,
	 * but the site is in development mode.
	 */
	public function test_should_always_return_original_js_when_in_dev_mode() {
		$compiler = new \_Beans_Compiler( array(
			'id'        => 'test',
			'type'      => 'script',
			'minify_js' => true,
		) );

		// Turn on development mode.
		update_option( 'beans_dev_mode', true );

		// Run the test.
		$this->assertSame( $this->js, $compiler->format_content( $this->js ) );
	}

	/**
	 * Test format_content() should return minified JavaScript when "minify_js" is enabled
	 * and the site is not in development mode.
	 */
	public function test_should_return_minified_js_when_not_in_dev_mode_and_minify_js_enabled() {
		$compiler = new \_Beans_Compiler( array(
			'id'        => 'test',
			'type'      => 'script',
			'minify_js' => true,
		) );

		// Turn off development mode.
		update_option( 'beans_dev_mode', false );

		$expected = <<<EOB
class MyGameClock{constructor(maxTime){this.maxTime=maxTime;this.currentClock=0;}
getRemainingTime(){return this.maxTime-this.currentClock;}}
EOB;
		// Run the test.
		$this->assertSame( $expected, $compiler->format_content( $this->js ) );
	}
}
