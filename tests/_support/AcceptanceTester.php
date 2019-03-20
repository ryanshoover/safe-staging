<?php


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
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

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
