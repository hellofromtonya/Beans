<?php
/**
 * Tests the replace_css_url method of _Beans_Compiler.
 *
 * @package Beans\Framework\Tests\Unit\API\Compiler
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Unit\API\Compiler;

use _Beans_Compiler;
use Beans\Framework\Tests\Unit\API\Compiler\Includes\Compiler_Test_Case;
use Brain\Monkey\Functions;

require_once dirname( __DIR__ ) . '/includes/class-compiler-test-case.php';

/**
 * Class Tests_Beans_Compiler_Replace_Css_Url
 *
 * @package Beans\Framework\Tests\Unit\API\Compiler
 * @group   unit-tests
 * @group   api
 */
class Tests_Beans_Compiler_Replace_Css_Url extends Compiler_Test_Case {

	/**
	 * Test replace_css_url() should return original content when there is no url source in the CSS.
	 */
	public function test_should_return_original_content_when_no_url() {
		$compiler = new _Beans_Compiler( array() );
		$css = <<<EOB
.home-page .tm-header {
    background-color: #195B7D;
    background-image: -webkit-gradient(linear,left top,left bottom,from(#195B7D),to(#43889A));
    background-image: -webkit-linear-gradient(top,#195B7D,#43889A);
    background-image: -moz-linear-gradient(top,#195B7D,#43889A);
    background-image: -ms-linear-gradient(top,#195B7D,#43889A);
    background-image: -o-linear-gradient(top,#195B7D,#43889A);
    background-image: linear-gradient(to bottom,#195B7D,#43889A);
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#195B7D',endColorstr='#43889A')";
    box-shadow: inset 0px -3px 20px rgba(0,0,0,0.04);
}
EOB;
		$this->assertSame( $css, $compiler->replace_css_url( $css ) );
	}

	/**
	 * Test replace_css_url() should return original content when it has a valid URI.
	 */
	public function test_should_return_original_content_when_valid_uri() {
		$compiler = new _Beans_Compiler( array() );
		$css = <<<EOB
.hero-section {
    background: linear-gradient(rgba(255, 255, 255, 0.8) 100%, #fff), 
                url(http://example.com/some-image.jpg) repeat center #fff;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
EOB;
		$this->assertSame( $css, $compiler->replace_css_url( $css ) );
	}

	/**
	 * Test replace_css_url() should convert the relative URL when it does not have ../.
	 */
	public function test_should_convert_relative_url_when_no_up_levels() {
		$compiler = new _Beans_Compiler( array() );

		// Set up the mocks.
		$this->set_current_fragment( $compiler, 'http://foo.com/assets/less/hero.less' );
		Functions\expect( 'is_main_site' )->andReturn( true );
		Functions\expect( 'site_url' )->andReturn( 'http://foo.com/' );

		$css      = <<<EOB
.hero-section {
    background-image: url(images/hero-2.jpg);
}
EOB;
		$expected = str_replace(
			'images/hero-2.jpg',
			'"http://foo.com/assets/less/images/hero-2.jpg"',
			$css
		);

		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );
	}

	/**
	 * Test replace_css_url() should convert the relative URL.
	 */
	public function test_should_convert_relative_url() {
		$compiler = new _Beans_Compiler( array() );

		// Set up the mocks.
		$this->set_current_fragment( $compiler, 'http://foo.com/assets/less/hero.less' );
		Functions\expect( 'is_main_site' )->andReturn( true );
		Functions\expect( 'site_url' )->andReturn( 'http://foo.com/' );

		// Test with no spaces, single quotes, or double quotes.
		$css      = <<<EOB
.hero-section {
    background-image: url(../images/hero-2.jpg);
}
EOB;
		$expected = str_replace(
			'../images/hero-2.jpg',
			'"http://foo.com/assets/images/hero-2.jpg"',
			$css
		);
		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );

		// Test with spaces and single quotes.
		$css      = <<<EOB
.hero-section {
    background-image: url( '../images/hero-2.jpg' );
}
EOB;
		$expected = str_replace(
			" '../images/hero-2.jpg' ",
			'"http://foo.com/assets/images/hero-2.jpg"',
			$css
		);
		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );

		// Test with spaces and double quotes.
		$css      = <<<EOB
.hero-section {
    background-image: url( "../images/hero-2.jpg" );
}
EOB;
		$expected = str_replace(
			' "../images/hero-2.jpg" ',
			'"http://foo.com/assets/images/hero-2.jpg"',
			$css
		);
		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );
	}

	/**
	 * Test replace_css_url() should convert a deeper relative URL.
	 */
	public function test_should_convert_deeper_relative_url() {
		$compiler = new _Beans_Compiler( array() );

		// Set up the mocks.
		$this->set_current_fragment( $compiler, 'http://example.com/assets/less/partials/hero.less' );
		Functions\expect( 'is_main_site' )->andReturn( true );
		Functions\expect( 'site_url' )->andReturn( 'http://example.com/' );

		// Test with no spaces, single quotes, or double quotes.
		$css      = <<<EOB
.hero-section {
    background-image: url(../../images/hero-1.jpg);
}
EOB;
		$expected = str_replace(
			'../../images/hero-1.jpg',
			'"http://example.com/assets/images/hero-1.jpg"',
			$css
		);
		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );

		// Test with spaces and single quotes.
		$css      = <<<EOB
.hero-section {
    background-image: url( '../../images/hero-1.jpg' );
}
EOB;
		$expected = str_replace(
			" '../../images/hero-1.jpg' ",
			'"http://example.com/assets/images/hero-1.jpg"',
			$css
		);
		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );

		// Test with spaces and double quotes.
		$css      = <<<EOB
.hero-section {
    background-image: url( "../../images/hero-1.jpg" );
}
EOB;
		$expected = str_replace(
			' "../../images/hero-1.jpg" ',
			'"http://example.com/assets/images/hero-1.jpg"',
			$css
		);
		$this->assertSame( $expected, $compiler->replace_css_url( $css ) );
	}
}
