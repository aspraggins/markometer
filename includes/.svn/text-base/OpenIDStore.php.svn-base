<?php

require_once( 'Auth/OpenID/Interface.php' );
require_once( 'Auth/OpenID/Nonce.php' );

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
class OpenIDStore extends Auth_OpenID_OpenIDStore{

	/**
	 * MySQL connection resource, database pre-selected
	 *
	 * @var resource id
	 */
	public $connection;
	
	/**
	 * Store an association
	 *
	 * @param string $server_url
	 * @param Auth_OpenID_Association $association
	 */
	function storeAssociation( $server_url, $association ){
		$server_url = mysql_real_escape_string( $server_url, $this->connection );
		$handle		= mysql_real_escape_string( $association->handle, $this->connection );
		$secret		= mysql_real_escape_string( $association->secret, $this->connection );
		$type		= mysql_real_escape_string( $association->assoc_type, $this->connection );
		
		mysql_query( "REPLACE INTO openid_associations ( server_url, handle, secret, issued, lifetime, assoc_type ) 
						VALUES ( '$server_url', '$handle', '$secret', '{$association->issued}', '{$association->lifetime}', '$type' )", $this->connection );
		
	}
	
	
	function getAssociation( $server_url, $handle = null, $preescaped = false ){
		if( !$preescaped )
			$server_url	= mysql_real_escape_string( $server_url, $this->connection );		
			
		$associations	= array();		
		
		if( $handle != null ){
			
			if( !$preescaped )
				$handle = mysql_real_escape_string( $handle, $this->connection );			
				
			$res = mysql_query( "SELECT handle, secret, issued, lifetime, assoc_type FROM openid_associations WHERE server_url='$server_url' AND handle='$handle'", $this->connection );
			if( $a = mysql_fetch_object( $res ) )
				array_push( $associations, new Auth_OpenID_Association( $a->handle, $a->secret, $a->issued, $a->lifetime, $a->assoc_type ) );
		}
		else{
			$res	= mysql_query( "SELECT handle, secret, issued, lifetime, assoc_type 
									FROM openid_associations 
									WHERE server_url='$server_url'", $this->connection );
			
			while( $a = mysql_fetch_object( $res ) )
				array_push( $associations, new Auth_OpenID_Association( $a->handle, $a->secret, $a->issued, $a->lifetime, $a->assoc_type ) );
		}
		
		
		$newest	 = null;
		if( count( $associations ) ){
			
			foreach( $associations as $assoc ){
				if( !$assoc->getExpiresIn() ){
					$this->removeAssociation( $server_url, $assoc->handle, false );
					continue;
				}				
					
				if( $newest == null ){
					$newest = $assoc;
				}
				else{
					if( $newest->issued < $assoc->issued )
						$newest = $assoc;
				}					
			}			
		}
		
		return $newest;
	}
	
	/**
	 * Delete an association
	 * 
	 * ...vars are previously escaped
	 *
	 * @param string $server_url
	 * @param string $handle
	 */
	function removeAssociation( $server_url, $handle, $check = true ){
	
		$server_url = mysql_real_escape_string( $server_url );
		$handle		= mysql_real_escape_string( $handle );

		if( $check && $this->getAssociation( $server_url, $handle, true ) == null )
			return false;
				
		
		mysql_query( "DELETE FROM openid_associations WHERE server_url = '$server_url' AND handle = '$handle'", $this->connection );
		return true;
	}
	
	function useNonce( $server_url, $timestamp, $salt ){
		global $Auth_OpenID_SKEW;
		

		if( abs( $timestamp - mktime() ) > $Auth_OpenID_SKEW )
            return false;
        
        $server_url	= mysql_real_escape_string( $server_url, $this->connection );
        $salt		= mysql_real_escape_string( $salt, $this->connection );

        mysql_query( "INSERT INTO openid_nonces ( server_url, timestamp, salt ) 
        				VALUES ( '$server_url', '$timestamp', '$salt' )", 
        					$this->connection );        
        return true;
	}
	
	
	function cleanupNonces(){		
		global $Auth_OpenID_SKEW;
        $v = time() - $Auth_OpenID_SKEW;
        
		mysql_query( "DELETE FROM openid_nonces WHERE timestamp < '$v'", $this->connection );
		return mysql_affected_rows( $this->connection );
	}
	
	
	function cleanupAssociations(){
		$t = time();
		mysql_query( "DELETE FROM openid_associations WHERE issued + lifetime < '$t'", $this->connection );
		return mysql_affected_rows( $this->connection );
	}
	
	function reset(){
        mysql_query( "DELETE FROM openid_associations", $this->connection );
        mysql_query( "DELETE FROM openid_nonces". $this->connection );
    }
}


?>