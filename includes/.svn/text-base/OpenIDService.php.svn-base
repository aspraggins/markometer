<?php

/**
 * OpenID Direct-To-MySQL Storage Framework
 *
 * This class acts as a direct MySQL store for the JanRain OpenID 2.0.0 
 * Framework.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, using version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author S. Alexandre Lemaire, saeven.net consulting inc. saeven@saeven.net
 */
class OpenIDService{

	/**
	 * Perform a login using an OpenID hostname
	 *
	 * @param unknown_type $uid
	 * @throws Exception
	 */
	public static function performOpenIDLogin( $uid ){
		if( !defined( "OPENID_ENABLED" ) || !OPENID_ENABLED )
			return null;
		
				
		if( defined( "OPENID_ALLOWED" ) ){
			$rexps = explode( "   ", OPENID_ALLOWED );
			$found = false;
			foreach( $rexps as $k ){
				if( preg_match( $k, $uid, $match ) ){
					$found = true;
					break;
				}
			}
			
			if( !$found )				
				throw new Exception( "We're sorry, your OpenID host is not in our list of permitted stores.", 0 );
			
		}
		
		//$permitted_hosts = explode( ",", OPENID_HOSTS );
		
		define('Auth_OpenID_RAND_SOURCE', null); 

		require_once "Auth/OpenID/Consumer.php";
		require_once "Auth/OpenID/SReg.php";
		require_once "Auth/OpenID/PAPE.php";
		
		global $pape_policy_uris;
		$pape_policy_uris = array(
			PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			PAPE_AUTH_MULTI_FACTOR,
			PAPE_AUTH_PHISHING_RESISTANT
		);		
		
		
		$store 				= new OpenIDStore(); // custom store
		$store->connection	= DB;
		$consumer			= new Auth_OpenID_Consumer( $store );
		$auth_request		= $consumer->begin( $uid );
		$auth_request->addExtension( Auth_OpenID_SRegRequest::build( array( 'email' ), array( 'nickname', 'fullname', 'postcode', 'country', 'timezone' ) ) );
		$auth_request->addExtension( new Auth_OpenID_PAPE_Request( $pape_policy_uris ) );
		
		if( $auth_request->shouldSendRedirect() ){
			$redirect_url = $auth_request->redirectURL( DOMAIN, DOMAIN . "index.php" );	
			redirectTo( $redirect_url );
		}
		else{
			 $form_id 	= 'openid_message';
			 $form_html = $auth_request->formMarkup( DOMAIN, DOMAIN . "index.php", false, array( 'id' => $form_id ) );
		
			 // Display an error if the form markup couldn't be generated;
			 // otherwise, render the HTML.
			 if( !Auth_OpenID::isFailure($form_html) )
				return $form_html;      			     
		}
		
		return null;			
	}
	
	
	public static function confirmOpenIDLogin(){			
		define( 'Auth_OpenID_RAND_SOURCE', null ); 
		
		require_once "Auth/OpenID/Consumer.php";
		require_once "Auth/OpenID/SReg.php";
		require_once "Auth/OpenID/PAPE.php";
		
		global $pape_policy_uris;
		$pape_policy_uris = array(
			PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			PAPE_AUTH_MULTI_FACTOR,
			PAPE_AUTH_PHISHING_RESISTANT
		);		
		
		$store 				= new OpenIDStore(); // custom store
		$store->connection	= DB;
		$consumer			= new Auth_OpenID_Consumer( $store );
		$response			= $consumer->complete( DOMAIN . "index.php" );		
											
		if( $response->status == Auth_OpenID_SUCCESS ){
			$openid 		= $response->getDisplayIdentifier();
			$sreg_resp		= Auth_OpenID_SRegResponse::fromSuccessResponse( $response );
			$sreg			= $sreg_resp->contents();
			$sreg['oid']	= $response->identity_url;
			return $sreg;			
		}
		else{
			echo $response->message;
		}
		
		return null;
	}
}

?>