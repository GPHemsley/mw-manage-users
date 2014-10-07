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
		$query['conds'][] = 'user_touched < 20100401000000';

		$query['options']['ORDER BY'] = 'user_touched ASC, user_editcount ASC';

		$this->mQuery = $query;

		return $query;
	}

	public function getStartBody()
	{
		ob_start();
		var_dump( $this->mQuery );
		$output = Html::rawElement( 'pre', array( 'style' => 'font-size: smaller;' ), ob_get_contents() );
		ob_end_clean();

		return $output;
	}

	public function formatRow( $row )
	{
		$username = $row->user_name;

		$output = Linker::userLink( $row->user_id, $username );
		$output .= ' &ndash; ' . $this->msg( 'usereditcount' )->numParams( $row->edits )->escaped();

		$Language = new Language();

		$output .= ' &ndash; ' . $Language->userTimeAndDate( $row->user_touched, $this->getUser() );

		ob_start();
		var_dump( $row );
		$output .= Html::rawElement( 'pre', array(), ob_get_contents() );
		ob_end_clean();

		return Html::rawElement( 'li', array(), $output );
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
