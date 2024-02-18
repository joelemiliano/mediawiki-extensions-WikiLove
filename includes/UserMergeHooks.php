<?php

// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

namespace MediaWiki\Extension\WikiLove;

use MediaWiki\Extension\UserMerge\Hooks\AccountFieldsHook;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * All hooks from the UserMerge extension which is optional to use with this extension.
 *
 * @file
 * @ingroup Extensions
 */

class UserMergeHooks implements AccountFieldsHook {
	private ILoadBalancer $loadBalancer;

	/**
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		ILoadBalancer $loadBalancer
	) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * Tables that Extension:UserMerge needs to update
	 *
	 * @param array &$updateFields
	 */
	public function onUserMergeAccountFields( array &$updateFields ): void {
		global $wgWikiLoveLogging;
		$dbr = $this->loadBalancer->getMaintenanceConnectionRef( DB_REPLICA );
		// FIXME HACK: The extension never actually required the 'wikilove_log' table
		// and would suppress db errors if it didn't exist
		if ( $wgWikiLoveLogging && $dbr->tableExists( 'wikilove_log', __METHOD__ ) ) {
			$updateFields[] = [ 'wikilove_log', 'wll_sender' ];
			$updateFields[] = [ 'wikilove_log', 'wll_receiver' ];
		}
	}

}
