<?php

// Establish page variables, objects, arrays, etc
Plugin::triggerEvent('page.start');

// Verify if user is logged in
$userService = new UserService();
$router = new Router();
$this->view->vars->loggedInUser = $userService->loginCheck();

$pageMapper = new PageMapper();
if (!empty($_GET['preview']) && is_numeric($_GET['preview'])) {
    // Parse preview request
    $page = $pageMapper->getPageById($_GET['preview']);
} else {
    // Parse the URI request
    $page = $pageMapper->getPageByCustom(array('slug' => trim($router->getRequestUri(), '/'), 'status' => 'published'));
}

// Validate requested page
if ($page) {
    $page_name = 'page_' . $page->slug;
    $this->view->vars->page = $page;
    $this->view->options->page = $page_name;
    $this->view->vars->meta = Language::GetMeta ($page_name);
    if (empty($this->view->vars->meta->title)) $this->view->vars->meta->title = $page->title;
} else {
    App::Throw404();
}

// Output Page
Plugin::triggerEvent('page.end');