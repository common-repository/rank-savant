<?php

class RANKSAVANT_INTEGRATION_API {

    public static $ranksavantDomainAPI = 'https://ranksavant.getrocketship.com/wp-json/api/v1';

    public static function get_key()
    {
        $api_key = get_option( 'ranksavant-key' );
        if (false !== $api_key) return $api_key;
        return '';
    }

    public static function get_status()
    {
        $status = get_option( 'ranksavant-verify-status' );
        if ('success' === $status) return true;
        return false;
    }

    public static function get_error_reason()
    {
        $message = get_option( 'ranksavant-verify-curl-body' );
        if (false !== $message) return $message;
        return false;
    }

    public static function get_containers_from_db()
    {
        $containers = get_option( 'ranksavant-containers' );
        if ($containers) return $containers;
        return [];
    }

    public static function get_containers()
    {
        $url = sprintf('%s/key/containers?key=%s', self::$ranksavantDomainAPI, self::get_key());
        $response = wp_remote_get(
            $url,
            array(
                'body'        => array(),
                'method'      => 'GET',
                'timeout'     => 20,
                'redirection' => 5,
                'httpversion' => '1.1',
                'headers'     => array(
                    'domain' => home_url(),
                ),
            )
        );
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            update_option('ranksavant-verify-status', 'error');
            update_option('ranksavant-verify-curl-body', $error_message);
        } else {
            $response_array = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( isset($response_array['status']) && true === $response_array['status'] ) {
                update_option('ranksavant-verify-status', 'success');
                update_option('ranksavant-containers', $response_array['containers']);
            } else {
                update_option('ranksavant-verify-status', 'error');
                update_option('ranksavant-verify-curl-body', $response_array['reason']);
            }
        }
    }

    public static function get_container($container_id)
    {
        global $wp;
        $current_page_url = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
        $url = sprintf('%s/key/container?key=%s&container=%s', self::$ranksavantDomainAPI, self::get_key(), $container_id);
        $request = wp_remote_get(
            $url,
            array(
                'body'        => array(),
                'method'      => 'GET',
                'timeout'     => 20,
                'redirection' => 5,
                'httpversion' => '1.1',
                'headers'     => array(
                    'domain' => home_url(),
                    'page' => $current_page_url,
                ),
            )
        );
        $body = wp_remote_retrieve_body( $request );
        return $body;
    }
}