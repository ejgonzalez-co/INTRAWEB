var objectRow = {            
	id: "",
	position_id: "",
	name: "",
	email: "",
	email_verified_at: "",
	password: "",
	remember_token: "",
	url_img_profile: "",
	url_digital_signature: "",
	uuid: "",
	created_at: "",
	updated_at: "",
	deleted_at: ""
};

var tableColumns = [
	{
	    name: 'id',
	    sortField: 'id',
	    visible: false
	},
	{
	    name: 'position_id',
	    sortField: 'position_id',
	    visible: true
	},
	{
	    name: 'name',
	    sortField: 'name',
	    visible: true
	},
	{
	    name: 'email',
	    sortField: 'email',
	    visible: true
	},
	{
	    name: 'email_verified_at',
	    sortField: 'email_verified_at',
	    visible: true
	},
	{
	    name: 'password',
	    sortField: 'password',
	    visible: true
	},
	{
	    name: 'remember_token',
	    sortField: 'remember_token',
	    visible: true
	},
	{
	    name: 'url_img_profile',
	    sortField: 'url_img_profile',
	    visible: true
	},
	{
	    name: 'url_digital_signature',
	    sortField: 'url_digital_signature',
	    visible: true
	},
	{
	    name: 'uuid',
	    sortField: 'uuid',
	    visible: true
	},
	{
	    name: 'created_at',
	    sortField: 'created_at',
	    visible: false
	},
	{
	    name: 'updated_at',
	    sortField: 'updated_at',
	    visible: false
	},
	{
	    name: 'deleted_at',
	    sortField: 'deleted_at',
	    visible: false
	},       
    {
        name: '__actions',
        dataClass: 'center aligned',
        callback: null
    }        
];