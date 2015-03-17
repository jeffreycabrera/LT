<?php
$config = array(
	'personal/process_leave' => array(
					array(
					    'field'   => 'startDate',
					    'label'   => 'Date Start',
					    'rules'   => 'required'
				 	),
					array(
					    'field'   => 'endDate',
					    'label'   => 'Date End',
					    'rules'   => 'required'
				 	),  
					array(
					    'field'   => 'reason',
					    'label'   => 'Reason',
					    'rules'   => 'trim|required|min_length[5]|xss_clean'
				 	)
				),
	'pending/action' => array(
					array(
					    'field'   => 'status',
					    'label'   => 'Status',
					    'rules'   => 'required'
				 	)
				),
	'login/index' => array(
					array(
					    'field'   => 'uid',
					    'label'   => 'User ID',
					    'rules'   => 'trim|required|xss_clean'
				 	),
					array(
					    'field'   => 'password',
					    'label'   => 'Password',
					    'rules'   => 'trim|required|xss_clean|sha1'
				 	)
				),
	'login/reset'=> array(
					array(
					    'field'   => 'npass',
					    'label'   => 'New Password',
					    'rules'   => 'trim|required|xss_clean|max_length[16]|min_length[8]|alpha_numeric'
				 	),
					array(
					    'field'   => 'cpass',
					    'label'   => 'Confirm Password',
					    'rules'   => 'trim|required|xss_clean|matches[npass]|max_length[16]|min_length[8]|alpha_numeric|sha1'
				 	)
				),
	'admin_add_edit' => array(
					array(
					    'field'   => 'lastName',
					    'label'   => 'Last Name',
					    'rules'   => 'trim|required|min_length[2]|xss_clean'
				 	),
				 	array(
					    'field'   => 'firstName',
					    'label'   => 'First Name',
					    'rules'   => 'trim|required|min_length[2]|xss_clean'
				 	),
				 	array(
					    'field'   => 'middleName',
					    'label'   => 'Middle Name',
					    'rules'   => 'trim|required|xss_clean'
				 	),
				 	array(
					    'field'   => 'emailAddress',
					    'label'   => 'Email',
					    'rules'   => 'valid_email|required'
				 	),
				 	array(
					    'field'   => 'PTO',
					    'label'   => 'PTO',
					    'rules'   => 'integer|required|trim'
				 	)
				 	// ,
				 	// array(
					 //    'field'   => 'approver1',
					 //    'label'   => 'Approver 1',
					 //    'rules'   => 'callback_checkAgainstUserID'
				 	// ),
				 	// array(
					 //    'field'   => 'approver2',
					 //    'label'   => 'Approver 2',
					 //    'rules'   => 'callback_checkAgainstUserID'
				 	// )
				),
	);
?>