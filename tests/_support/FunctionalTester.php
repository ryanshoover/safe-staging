<?php
// phpcs:ignoreFile Disabling IDE linting

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
	use _generated\FunctionalTesterActions;

	/**
	 * Add our permalink structure and setup rewrite rules
	 *
	 * @link https://github.com/lucatume/wp-browser/issues/190
	 */
	public function haveRewriteRules() {
		$this->haveOptionInDatabase(
			'permalink_structure',
			'/blog/%postname%/'
		);

		$this->loginAsAdmin();
		$this->amOnAdminPage( 'options-permalink.php' );
		$this->seeOptionInDatabase(
			[
				'option_name' => 'rewrite_rules',
			]
		);
	}

	/**
	 * Activate a plugin in the database.
	 *
	 * @param string $plugin Pass in the plugin as `plugin-folder/plugin-file.php`.
	 */
	public function activatePluginInDB( $plugin ) {
		$plugins = $this->grabOptionFromDatabase( 'active_plugins' );
		$plugins = $plugins ?? [];
		$plugins[] = $plugin;
		$this->haveOptionInDatabase( 'active_plugins', $plugins );
	}
}
