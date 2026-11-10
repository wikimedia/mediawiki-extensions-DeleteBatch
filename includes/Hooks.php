<?php

namespace MediaWiki\Extension\DeleteBatch;

use AdminLinksHook;
use ALItem;
use ALRow;
use ALTree;

class Hooks implements AdminLinksHook {
	/**
	 * @param ALTree &$admin_links_tree
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAdminLinks( ALTree &$admin_links_tree ) {
		$general_section = $admin_links_tree->getSection( wfMessage( 'adminlinks_general' )->text() );
		$extensions_row = $general_section->getRow( 'extensions' );
		if ( $extensions_row === null ) {
			$extensions_row = new ALRow( 'extensions' );
			$general_section->addRow( $extensions_row );
		}
		$extensions_row->addItem( ALItem::newFromSpecialPage( 'DeleteBatch' ) );
		return true;
	}
}
