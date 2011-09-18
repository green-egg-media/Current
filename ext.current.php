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
    var $version 			= '1.1';
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
	 *
	 * @param $session_data
	 * @return array The session data.
	 */
    function set_current( $session_data )
    {
		$this->EE->load->helper('url');

		//Current URL
		$this->EE->config->_global_vars['current_url'] = current_url();

		//Total URL Segments
		$this->EE->config->_global_vars['total_url_segments'] = $this->EE->uri->total_segments();

		//URI String
		$this->EE->config->_global_vars['uri_string'] = $this->EE->uri->uri_string();

		//Last URL Segment
		$this->EE->config->_global_vars['last_url_segment'] = end( $this->EE->uri->segment_array() );

		//Tracker Segments 1 - 6. tracker_1 being the last EE page visited, tracker_2 the page before that, etc.
		$tracker = $session_data->tracker;
		for ( $i = 0; $i < 6; $i++ )
		{
			$this->EE->config->_global_vars['tracker_'.($i + 1)] = ( isset( $tracker[$i] ) ) ? $tracker[$i] : '';
		}
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
			'hook'		=> 'sessions_end',
			'settings'	=> '',
			'priority'	=> 6,
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		$this->EE->db->insert('extensions', $data);
	}

	// --------------------------------------------------------------------------
	  
	/**
	 * Activate Extension
	 *
	 * @return void
	 */
	function update_extension($current = '')
	{
		if ($current == '' or $current == $this->version) return false;
				
		// If this is 1.1 and up, update the 
		// extension hook used from sessions_start
		// to sessions_end
		if ($current <= '1.0')
		{
			$this->EE->db->where('class', __CLASS__);
			$this->EE->db->update('extensions', 
						array(
							'hook' 		=> 'session_end',
							'version' 	=> $this->version
						)
			);
		}
		
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