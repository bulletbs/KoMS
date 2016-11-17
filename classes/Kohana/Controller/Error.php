<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Error extends Controller_System_Page
{
    /**
     * @var string
     */
    protected $_requested_page;

    /**
     * @var string
     */
    protected $_message;

    /**
     * Pre determine error display logic
     */
    public function before()
    {
        parent::before();

        // Sub requests only!
        if ( ! $this->request->is_initial())
        {
            if ($message = rawurldecode($this->request->param('message')))
            {
                $this->_message = $message;
            }

            if ($requested_page = rawurldecode($this->request->param('origuri')))
            {
                $this->_requested_page = $requested_page;
            }
        }
        else
        {
            // This one was directly requested, don't allow
            $this->request->action(404);

            // Set the requested page accordingly
            $this->_requested_page = Arr::get($_SERVER, 'REQUEST_URI');
        }

        $this->response->status((int) $this->request->action());
    }

    /**
     * Serves HTTP 403 error page
     */
    public function action_403()
    {
        $this->template->description = __('Forbidden');
        $this->template->keywords = __('forbidden, 403');
        $this->template->title = __('Forbidden');

        $this->template->content = View::factory('error/403')
            ->set('page_title', __('Forbidden'))
            ->set('error_message', $this->_message)
            ->set('requested_page', $this->_requested_page);
    }

    /**
     * Serves HTTP 404 error page
     */
    public function action_404()
    {
        $this->template->description = __('The requested page not found');
        $this->template->keywords = __('not found, 404');
        $this->template->page_title = __('Page not found');

        $this->template->content = View::factory('error/404')
            ->set('page_title', __('Page not found'))
            ->set('error_message', $this->_message);
    }

    /**
     * Serves HTTP 410 error page
     */
    public function action_410()
    {
        $this->template->description = __('Page expired');
        $this->template->keywords = __('expired, 410');
        $this->template->page_title = __('The requested page is no longer available');

        $this->template->content = View::factory('error/410')
            ->set('page_title', __('Page expired'))
            ->set('error_message', $this->_message);
    }

    /**
     * Serves HTTP 500 error page
     */
    public function action_500()
    {
        $this->template->description = 'Internal server error occured';
        $this->template->keywords = 'server error, 500, internal error, error';
        $this->template->title = 'Internal server error occured';

        $this->template->content = View::factory('error/500')
            ->set('error_message', $this->_message)
        ;
    }
}