<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by JetBrains PhpStorm.
 * User: bullet
 * Date: 26.02.12
 * Time: 15:02
 * To change this template use File | Settings | File Templates.
 */
 
class Kohana_Controller_Admin_Main extends Controller_System_Admin{

    public $auth_required = FALSE;

    public $secure_actions = array(
        'index' => 'admin',
        'clear' => 'admin',
    );

    /**
     * Skip automatic rendering actions
     * @var array
     */
    public $skip_auto_render = array(
        'login',
        'logout',
        'clear',
        'clearstyle',
        'clearcache',
        'clearboard',
        'sitemap',
    );

    public function action_index(){
        $modules = Kohana::modules();
        $counters = array();
        if(isset($modules['catalog'])){
            $this->template->content
                ->set('moderate_catalog', ORM::factory('CatalogCompany')->countNotModerated())
            ;
            $counters['catalog'] = ORM::factory('CatalogCompany')->count_all();
        }
        if(isset($modules['board'])){
            $this->template->content
                ->set('moderate_board', ORM::factory('BoardAd')->countNotModerated())
            ;
            $counters['board'] = ORM::factory('BoardAd')->count_all();
        }

        $counters['pages'] = ORM::factory('Page')->count_all();
        $counters['users'] = ORM::factory('User')->count_all();
        if(isset($modules['news'])){
            $counters['news'] = ORM::factory('News')->count_all();
        }

        $this->template->content
            ->set('moderate_comments', Comments::countNotModerated())
            ->set('counters', $counters)
            ->set('modules', $modules)
            ->set('name', $this->config['project']['name'])
        ;
    }


    public function action_login(){
        $goto = Arr::get($_REQUEST,'goto', 'admin/main');

        if(Auth::instance()->logged_in() && !$this->request->is_ajax()) {
            $this->go('admin');
            return;
        }

        if(Arr::get($_POST,'submit') !== NULL){
            $data = Arr::extract($_POST, array('username', 'password', 'remember'));
            try{
                if(Auth::instance()->login($data['username'], $data['password'], $data['remember'] == 1 ? TRUE : FALSE)){
                    $this->go($goto);
                }
                else
                    $errors = array(__("Invalid user login or password"));
            }
            catch(ORM_Validation_Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        $this->template = View::factory('admin/main/login');
        $this->template->bind('errors', $errors);
        $this->template->bind('goto', $goto);
        $this->template->styles = array();
        $this->template->scripts = array();
        $this->template->styles[] = 'media/libs/bootstrap/css/bootstrap.min.css';
        $this->template->styles[] =  'assets/koms/css/admin.css';
        $this->template->scripts[] = 'media/libs/jquery-1.11.1.min.js';
        $this->template->scripts[] = 'media/libs/bootstrap/js/bootstrap.min.js';

        $this->template->title = $this->config['project']['name'];

        $this->response->body($this->template);
    }

    public function action_logout(){
        if($this->logged_in)
            Auth::instance()->logout(true, true);
        $this->go('admin/login');
    }

    public function action_clear(){
        Cache::instance()->delete_all();

        AssetsManager::instance()->cleanAssets();
        if(class_exists('BoardCache'))
            BoardCache::instance()->cleanData('*');

        Flash::success(__('Cache successfully cleared'));
        $this->redirect('admin');
    }

    public function action_clearstyle(){
        AssetsManager::instance()->cleanAssets();
        Flash::success(__('Cache successfully cleared'));
        $this->redirect('admin');
    }

    public function action_clearcache(){
        Cache::instance()->delete_all();
        Flash::success(__('Cache successfully cleared'));
        $this->redirect('admin');
    }

    public function action_clearboard(){
        if(class_exists('BoardCache'))
            BoardCache::instance()->cleanData('*');
        Flash::success(__('Cache successfully cleared'));
        $this->redirect('admin');
    }

    const SITEMAP_LINKS_LIMIT = 10000;
    const SITEMAP_FOLDER = "media/upload/sitemap/";
    const SITEMAP_INDEX_FOLDER = "";

    public function action_sitemap(){
        $config = Kohana::$config->load('module_sitemap')->as_array();
        $sitemap_index = array();

        foreach($config as $module){
            $links = array();
            foreach($module['sources'] as $_source){
                $links = array_merge($links, ORM::factory($_source['model'])->{$_source['get_links_method']}());
            }

            $sitemap = new Sitemap();
            $file = DOCROOT . self::SITEMAP_FOLDER . $module['name'] .".xml";
            $sitemap_link = URL::base('http'). self::SITEMAP_FOLDER . $module['name'] .".xml";
            foreach($links as $_link_id => $_link){
                $url = new Sitemap_URL;
                $url->set_loc(URL::base('http').$_link)
                    ->set_last_mod(time())
                    ->set_change_frequency('weekly')
                    ->set_priority($module['priority']);
                $sitemap->add($url);
                /* При достижении лимита открываем второй файл */
                if($_link_id>0 && $_link_id % self::SITEMAP_LINKS_LIMIT == 0){
                    $response = urldecode($sitemap->render());
                    file_put_contents($file, $response);
                    $sitemap_index[] = $sitemap_link;
                    unset($sitemap);
                    unset($response);

                    $sitemap = new Sitemap();
                    $file = DOCROOT . self::SITEMAP_FOLDER . $module['name'] ."_". ($_link_id / self::SITEMAP_LINKS_LIMIT + 1) .".xml";
                    $sitemap_link = URL::base('http'). self::SITEMAP_FOLDER . $module['name'] ."_". ($_link_id / self::SITEMAP_LINKS_LIMIT + 1) .".xml";
                }
            }
            $response = urldecode($sitemap->render());
            file_put_contents($file, $response);
            $sitemap_index[] = $sitemap_link;
            unset($sitemap);
            unset($response);
        }
        $index = new SitemapIndex();
        foreach($sitemap_index as $_link){
            $sitemap = new SitemapIndex_URL();
            $sitemap->set_loc($_link)->set_last_mod(time());
            $index->add($sitemap);
        }
        $file = DOCROOT . self::SITEMAP_FOLDER . 'sitemap.xml';
//        $response = urldecode($index->render());
        $response = $index->render();
        file_put_contents($file, $response);
    }
}