<?php

class InactiveUsersPager extends UsersPager
{
	public function __construct( $options = array() )
	{
		parent::__construct();

		$this->mOptions = $options;
	}

	public function getQueryInfo()
	{
		$query = parent::getQueryInfo();

		$query['fields'][] = 'user_touched';

		$query['conds'][] = 'user_editcount = 0';

		var_dump($query);

		return $query;
	}

	public function formatRow( $row )
	{
		ob_start();
		var_dump( $row );
		$row_dump = ob_get_contents();
		ob_end_clean();

		return Html::rawElement( 'li', array(), Html::rawElement( 'pre', array(), $row_dump ) );
	}
}

class SpecialManageUsers extends SpecialPage
{
	public function __construct()
	{
		parent::__construct( 'ManageUsers', 'manageusers', true );
	}

	public function execute( $par )
	{
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		# Do stuff
		# ...
		$wikitext = 'Hello world!';
		$output->addWikiText( $par );
		$output->addWikiText( $param );
		$output->addWikiText( $wikitext );

		switch ( $par ) {
			case 'test':
				$output->addWikiText( 'Test stuff' );
			break;

			default:
				$output->addWikiText( 'Main page stuff' );

				$up = new InactiveUsersPager();

				$s = '';
				if ( !$this->including() ) {
					$s = $up->getPageHeader();
				}

				$usersbody = $up->getBody();

				if ( $usersbody ) {
					$s .= $up->getNavigationBar();
					$s .= Html::rawElement( 'ul', array(), $usersbody );
					$s .= $up->getNavigationBar();
				} else {
					$s .= $this->msg( 'listusers-noresult' )->parseAsBlock();
				}

				$this->getOutput()->addHTML( $s );
			break;
		}
	}
}
