<?php

$title = 'Data Pribadi';
$edit = find_by_id('users', (int) current_user()['id']);

render('profile_view', compact('title', 'edit'));
