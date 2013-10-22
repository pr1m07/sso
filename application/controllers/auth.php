<?php
/**
 * Auth Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class Auth extends CI_Controller {
    
    function Auth() {
        parent::__construct();
        $this->load->model('user');
    }

    public function session($provider) {

        $this->load->helper('url_helper');

        $provider = $this->oauth2->provider($provider, array(
            'id' => '250578781756854',
            'secret' => '5c82761f1b0c3d3cc6ebb49991550b28',
        ));

        if ( ! $this->input->get('code'))
        {
            // By sending no options it'll come back here
            $provider->authorize();
        }
        else
        {
            try
            {
                $token = $provider->access($_GET['code']);
                $user = $provider->get_user_info($token);

                

                echo "<pre>Tokens: ";
                var_dump($token);

                echo "\n\nUser Info: ";
                var_dump($user);
            }

            catch (OAuth2_Exception $e)
            {
                show_error('That didnt work: '.$e);
            }

        }
    }
}

?>