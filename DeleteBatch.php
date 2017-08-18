<?php
/**
 * DeleteBatch - a special page to delete a batch of pages
 *
 * @file
 * @ingroup Extensions
 * @author Bartek Łapiński <bartek@wikia-inc.com>
 * @version 1.6.0
 * @license https://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @link https://www.mediawiki.org/wiki/Extension:DeleteBatch Documentation
 */

// Ensure that the script cannot be executed outside of MediaWiki.
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This is an extension to MediaWiki and cannot be run standalone.' );
}
// Extension credits that will show up on Special:version
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'Delete Batch',
	'version' => '1.6.0',
	'author' => array(
		'Bartek Łapiński',
		'...'
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:DeleteBatch',
	'descriptionmsg' => 'deletebatch-desc',
	'license-name' => 'GPL-2.0+'
);

// New user right, required to use Special:DeleteBatch
$wgAvailableRights[] = 'deletebatch';
$wgGroupPermissions['bureaucrat']['deletebatch'] = true;

// Set up the new special page
$wgMessagesDirs['DeleteBatch'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['DeleteBatchAlias'] = __DIR__ . '/DeleteBatch.alias.php';
$wgAutoloadClasses['SpecialDeleteBatch'] = __DIR__ . '/DeleteBatch.body.php';
$wgAutoloadClasses['DeleteBatchForm'] = __DIR__ . '/DeleteBatch.body.php';
$wgSpecialPages['DeleteBatch'] = 'SpecialDeleteBatch';

// Hooks
$wgHooks['AdminLinks'][] = 'SpecialDeleteBatch::addToAdminLinks'; // Admin Links extension
