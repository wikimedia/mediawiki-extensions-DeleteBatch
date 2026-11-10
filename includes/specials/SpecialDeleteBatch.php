<?php

use MediaWiki\Exception\UserBlockedError;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\UserFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ILBFactory;

class SpecialDeleteBatch extends SpecialPage {
	public function __construct(
		private readonly IConnectionProvider $dbProvider,
		private readonly ILBFactory $lbFactory,
		private readonly LinkRenderer $linkRenderer,
		private readonly RepoGroup $repoGroup,
		private readonly UserFactory $userFactory,
		private readonly WikiPageFactory $wikiPageFactory,
	) {
		parent::__construct( 'DeleteBatch' );
	}

	/** @inheritDoc */
	public function getRestriction(): string {
		return 'deletebatch';
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/**
	 * Show the special page
	 *
	 * @param string|null $par Parameter passed to the page, if any
	 * @throws UserBlockedError
	 * @return void
	 */
	public function execute( $par ) {
		# Check permissions
		$user = $this->getUser();
		if ( !$user->isAllowed( 'deletebatch' ) ) {
			$this->displayRestrictionError();
		}

		# Show a message if the database is in read-only mode
		$this->checkReadOnly();

		# If user is blocked, they don't need to access this page
		$block = $user->getBlock();
		if ( $block ) {
			throw new UserBlockedError( $block );
		}

		$this->getOutput()->setPageTitle( $this->msg( 'deletebatch-title' )->escaped() );
		$cSF = new DeleteBatchForm(
			$this->dbProvider,
			$this->lbFactory,
			$this->linkRenderer,
			$this->repoGroup,
			$this->userFactory,
			$this->wikiPageFactory,
			$this->getPageTitle(),
			$this->getContext()
		);

		$request = $this->getRequest();
		$action = $request->getRawVal( 'action' );
		if ( $action === 'success' ) {
			/* do something */
		} elseif ( $request->wasPosted() && $action === 'submit' &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			$cSF->doSubmit();
		} else {
			$cSF->showForm();
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function getGroupName() {
		return 'pagetools';
	}
}
