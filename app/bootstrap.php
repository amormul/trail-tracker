<?php
function loadConfig(): void
{
    $configFile =  '..' . DIRECTORY_SEPARATOR . '.conf';
    if (!file_exists($configFile)) {
        throw new Exception('File ' . $configFile . ' does not exist');
    }
    $handle = fopen($configFile, 'r');
    if (!$handle) {
        var_dump('not open   ' . $configFile);
        throw new Exception('Cannot open .conf file');
    }
    while (!feof($handle)) {
        $line = trim(fgets($handle));
        //if(!empty($line) && !str_starts_with($line, '#')){
        if (!empty($line) && strpos($line, '#') !== 0) {
            putenv($line);
        }
    }
}
function conf($key): string
{
    return getenv($key);
}


spl_autoload_register(function ($class) {
    $path = '..' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        include_once $path;
        return true;
    }
    return false;
});
if (conf('DEBUG') === 'true') {
    loadConfig();
    new \app\core\Route();
} else {
    try {
        loadConfig();
        new \app\core\Route();
    } catch (Exception $e) {
        //    запис в log про конкретну помилку
        var_dump($e->getMessage());
        \app\core\Logs::write($e->getMessage());
        //    показати сторінку що щось пішло не так
        $view = new \app\core\View();
        $view->render('error', ['title' => 'oops', 'message' => ' boorstrap '.  $e->getMessage()]);
    }
}
