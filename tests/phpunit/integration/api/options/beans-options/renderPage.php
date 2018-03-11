<?php
/**
 * Tests for page() method of the _Beans_Options.
 *
 * @package Beans\Framework\Tests\Integration\API\Options
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Integration\API\Options;

use _Beans_Options;
use Beans\Framework\Tests\Integration\API\Options\Includes\Options_Test_Case;
use Brain\Monkey;

require_once dirname( __DIR__ ) . '/includes/class-options-test-case.php';

/**
 * Class Tests_Beans_Options_Render_Page
 *
 * @package Beans\Framework\Tests\Integration\API\Options
 * @group   api
 * @group   api-options
 */
class Tests_Beans_Options_Render_Page extends Options_Test_Case {

	/**
	 * Test register() should return null when the page does not have a metabox.
	 */
	public function test_should_return_null_when_page_does_not_have_metabox() {
		$this->assertNull( ( new _Beans_Options() )->page( 'beans_settings' ) );
	}

	/**
	 * Test register() should return render form when "normal" context is configured.
	 */
	public function test_should_render_form_when_context_normal() {
		$instance = new _Beans_Options();
		$this->go_to_settings_page();

		// Register the first option.
		$option = current( static::$test_data );
		$instance->register( $option['section'], $option['args'] );

		// Run the method and grab the HTML out of the buffer.
		ob_start();
		( new _Beans_Options() )->page( 'themesphppagebeans_settings' );
		$html = ob_get_clean();
		$html = $this->format_the_html( $html );

		// Run the tests. Exclude checking for the nonce fields.
		$expected = <<<EOB
<form action="" method="post" class="bs-options" data-page="">
EOB;
		$this->assertContains( $this->format_the_html( $expected ), $html );

		$expected = <<<EOB
	<div class="metabox-holder">
		<div id="normal-sortables" class="meta-box-sortables">
			<div id="compiler_options" class="postbox " >
				<button type="button" class="handlediv" aria-expanded="true">
					<span class="screen-reader-text">Toggle panel: Compiler options</span>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</button>
				<h2 class='hndle'><span>Compiler options</span></h2>
				<div class="inside"></div>
			</div>
		</div>
	</div>
	<p class="bs-options-form-actions">
		<input type="submit" name="beans_save_options" value="Save" class="button-primary">
		<input type="submit" name="beans_reset_options" value="Reset" class="button-secondary">
	</p>
</form>
EOB;
		$this->assertContains( $this->format_the_html( $expected ), $html );
	}

	/**
	 * Test register() should return render form when "column" context is configured.
	 */
	public function test_should_render_form_when_column_context() {
		$instance = new _Beans_Options();
		$this->go_to_settings_page();

		// Register the options.
		foreach ( static::$test_data  as $option ) {
			$instance->register( $option['section'], $option['args'] );
		}

		global $wp_meta_boxes;
		$this->assertArrayHasKey( 'themesphppagebeans_settings', $wp_meta_boxes );

		// Run the method and grab the HTML out of the buffer.
		ob_start();
		( new _Beans_Options() )->page( 'themesphppagebeans_settings' );
		$html = ob_get_clean();
		$html = $this->format_the_html( $html );

		// Run the tests. Exclude checking for the nonce fields.
		$expected = <<<EOB
<form action="" method="post" class="bs-options" data-page="">
EOB;
		$this->assertContains( $this->format_the_html( $expected ), $html );

		$expected = <<<EOB
	<div class="metabox-holder column">
		<div id="normal-sortables" class="meta-box-sortables">
			<div id="compiler_options" class="postbox " >
				<button type="button" class="handlediv" aria-expanded="true">
					<span class="screen-reader-text">Toggle panel: Compiler options</span>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</button>
				<h2 class='hndle'><span>Compiler options</span></h2>
				<div class="inside"></div>
			</div>
		</div>
		<div id="column-sortables" class="meta-box-sortables">
			<div id="images_options" class="postbox " >
				<button type="button" class="handlediv" aria-expanded="true">
					<span class="screen-reader-text">Toggle panel: Images options</span>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</button>
				<h2 class='hndle'><span>Images options</span></h2>
				<div class="inside"></div>
			</div>
			<div id="mode_options" class="postbox " >
				<button type="button" class="handlediv" aria-expanded="true">
					<span class="screen-reader-text">Toggle panel: Mode options</span>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</button>
				<h2 class='hndle'><span>Mode options</span></h2>
				<div class="inside"></div>
			</div>				
		</div>
	</div>
	<p class="bs-options-form-actions">
		<input type="submit" name="beans_save_options" value="Save" class="button-primary">
		<input type="submit" name="beans_reset_options" value="Reset" class="button-secondary">
	</p>
</form>
EOB;
		$this->assertContains( $this->format_the_html( $expected ), $html );
	}
}
