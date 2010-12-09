<?php

/**
 * Current Extension 
 *
 * Gets some information on current URL as Global Variables.
 *
 * @package		current_ext
 * @category	Extension
 * @author		Adam Fairholm (Green Egg Media)
 * @link		https://github.com/green-egg-media/Current
 */
 
class Current_ext {
    
    var $name 				= 'Current';
    var $version 			= '1.0';
    var $description 		= 'Adds some variables about current page as global variables';
    var $settings_exist 	= 'n';
    var $docs_url 			= 'https://github.com/green-egg-media/Current';
 
    var $settings 			= array();
 
	// -------------------------------------------------------------------------- 
   
    function Current_ext($settings='')
    {
    	$this->EE =& get_instance();
    
        $this->settings = $settings;
    }

	// -------------------------------------------------------------------------- 
    
    /**
     * Set some current data.
     */
    function set_current()
    {
    	$this->EE->load->helper('url');
    
    	/**
    	 * Current URL
    	 */
		$this->EE->config->_global_vars['current_url'] = current_url();
 
     	/**
    	 * Total URL Segments
    	 */
		$this->EE->config->_global_vars['total_url_segments'] = $this->EE->uri->total_segments();

     	/**
    	 * Last URL Segment
    	 */
		$segments = $this->EE->uri->segment_array();
		
		$total = count($segments);
		
		if( $total == 0 ):
		
			$last = '';
		
		else:
		
			$last = $segments[$total];
		
		endif;

		$this->EE->config->_global_vars['last_url_segment'] = $last;
    }

	// --------------------------------------------------------------------------
	  
	/**
	 * Activate Extension
	 *
	 * @return void
	 */
	function activate_extension()
	{
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'set_current',
			'hook'		=> 'sessions_start',
			'settings'	=> '',
			'priority'	=> 6,
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		$this->EE->db->insert('extensions', $data);
	}

	// --------------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}
	
}

/* End of file ext.current.php */
/* Location: ./system/expressionengine/third_party/ext.current.php */