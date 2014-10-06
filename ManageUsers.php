<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'ManageUsers',
	'descriptionmsg' => 'manageusers-desc',
	'version' => '0.0.1-dev',
	'author' => array( 'Gordon P. Hemsley' ),
	'url' => 'https://github.com/GPHemsley/mw-manage-users',
	'license-name' => 'LICENSE',
);

$wgAutoloadClasses['SpecialManageUsers'] = __DIR__ . '/includes/specials/SpecialManageUsers.php';

$wgMessagesDirs['ManageUsers'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['ManageUsersAlias'] = __DIR__ . '/ManageUsers.alias.php';

$wgSpecialPageGroups['ManageUsers'] = 'users';
$wgSpecialPages['ManageUsers'] = 'SpecialManageUsers';

$wgAvailableRights[] = 'manageusers';

// By default, assign 'manageusers' right to every group that has 'userrights' right.
foreach ( $wgGroupPermissions as $group => $permissions ) {
	if ( isset( $permissions['userrights'] ) ) {
		$wgGroupPermissions[$group]['manageusers'] = $permissions['userrights'];
	}
}


