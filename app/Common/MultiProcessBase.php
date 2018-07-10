<?php
/**
 * 多进程处理脚本基类.
 *
 * @author shangyuh <shangyuh@jumei.com>
 */

namespace Script;

/**
 * Class MultiProcessBase.
 *
 * 多进程并发处理空值程序.
 *
 * 使用方法(一):
 *
 *  MultiProcessBase::instance()->scheduler($scheduler)->processor(function($key, $value, $p) {})->bootstrap();
 *
 * 使用方法(二):
 *
 *  MultiProcessBase::instance()->bootstrap($scheduler, function($key, $value, $p) {});
 */

class MultiProcessBase
{
    /**
     * 存放子进程PID列表.
     *
     * @var array
     */
    protected $pids = array();

    /**
     * 父进程的PID号.
     *
     * @var integer
     */
    protected $mainPid;

    /**
     * 设置默认log目录.
     */
    public $logDir = '../storage/logs';

    /**
     * 获取进程PID处理的日志目录.
     *
     * @param string $path 设置程序可写的目录.
     *
     * @return $this
     */
    public function setLogDir($path)
    {
        $this->logDir = rtrim($path, '/');
        return $this;
    }

    /**
     * 获取父进程PID.
     *
     * @param array $scheduler 调度参数数组. 没个进程处理里面的一项.
     *
     * @return $this
     */
    public function getParentPid(array $scheduler)
    {
        return $this->mainPid;
    }

    /**
     * 获取一个新实例.
     *
     * @return MultiProcessBase
     */
    public static function instance()
    {
        return new self;
    }

    /**
     * 当前进程的PID.
     *
     * @var integer
     */
    protected $currentPid;

    /**
     * 调度数据.
     *
     * @var array
     */
    protected $scheduler = array();

    /**
     * 当前的调度KEY信息.
     *
     * @var mixed
     */
    public $currentKey;

    /**
     * 设置调度器调度数据.
     *
     * @param array $scheduler 调度参数数组. 没个进程处理里面的一项.
     *
     * @return $this
     */

    public function scheduler(array $scheduler)
    {
        $this->scheduler = $scheduler;
        return $this;
    }

    /**
     * 获取日志文件路径.
     *
     * @return string
     */
    public function getLogFilePath()
    {
        return $this->logfile;
    }

    /**
     * 可调用的变量.
     *
     * @var callable
     */
    protected $processor;

    /**
     * 设置调度器调度数据.
     *
     * @param callable $processor 调度参数数组. 没个进程处理里面的一项.
     *
     * @return $this
     */
    public function processor($processor)
    {
        $this->processor = $processor;
        return $this;
    }

    /**
     * 多进程并行处理入口.
     *
     * @param array    $scheduler 调度参数数组. 每个进程处理里面的一项.
     * @param callable $processor 可回调的处理器函数.
     *
     * @return $this
     *
     * @throws \Exception 参数错误.
     */
    public function bootstrap(array $scheduler = array(), $processor = null)
    {
        if (!empty($scheduler)) {
            $this->scheduler = $scheduler;
        }
        if (!empty($processor)) {
            $this->processor = $processor;
        }
        if (empty($this->scheduler) || empty($this->processor) || !is_callable($this->processor)) {
            throw new \Exception("多进程初始化参数错误.");
        }
        // 设置父进程ID
        $this->mainPid = posix_getpid();
        $this->output(sprintf("父进程PID: %d 启动, 将开启子进程: %d 个", $this->mainPid, count($this->scheduler)));
        $path = $this->logDir;
        if (!file_exists($path)) {
            throw new \Exception("日志目录不存在: {$path}");
        }
        $logfile = sprintf('fork-result-%s.txt', date('Ymd-His'));
        $this->logfile = $path . '/' . $logfile;
        $this->output(sprintf("所有子进程日志文件位于: %s", $this->logfile));

        foreach ($this->scheduler as $key => $v) {
            $curPid = pcntl_fork(); // 产生子进程，而且从当前行之下开试运行代码，而且不继承父进程的数据信息
            if ($curPid == -1) {
                $this->output("进程创建失败...")->stop();
            } elseif (!$curPid) {
                $this->pids[$key] = $this->currentPid = $curPid = getmypid(); // posix_getpid();
                // 子进程处理程序.
                $this->output(sprintf("开启子进程: %d", $curPid));
                $this->currentKey = $key;
                call_user_func($this->processor, $key, $v, $this);
                $this->stop();
            } else {
                // 父进程处理程序
                $this->pids[$key] = $this->currentPid = $curPid;
            }
        }
        $this->init();
        $count = count($this->scheduler);
        for ($i = 0; $i < $count; $i++) {
            $exit = pcntl_waitpid(-1, $status);
            pcntl_signal_dispatch();
            $this->output("> 子进程:{$exit} 退出...");
        }
        return $this;
    }

    /**
     * 进程处理完成后回调函数.
     *
     * @param callable $callback 可回调的函数.
     *
     * @return $this
     */
    public function callback($callback = null)
    {
        if (is_callable($callback)) {
            call_user_func($callback, $this);
        }
        return $this;
    }

    /**
     * 清理kill所有子进程.
     *
     * @return mixed
     */
    protected function killAll()
    {
        clearstatcache();
        foreach ($this->pids as $pid) {
            // posix_kill($pid, SIGTERM);
            posix_kill($pid, SIGKILL);
        }
    }

    /**
     * 设置信号接受.
     *
     * @return mixed
     */
    protected function init()
    {
        declare(ticks = 1);
        // pcntl_signal(SIGCHLD, array($this, 'signalHandler'));
        pcntl_signal(SIGINT, array($this, 'signalHandler'));
        posix_setsid(); // set session leader
    }

    /**
     * 信号处理程序.
     *
     * @param integer $signo 信号.
     *
     * @return mixed
     */
    public function signalHandler($signo)
    {
        switch ($signo) {
            case SIGINT:
                $this->output(PHP_EOL . "终止所有处理子进程...");
                $this->killAll();
                break;
            case SIGTERM:
                // 处理SIGTERM信号
                $this->output("进程退出...")->stop();
                break;
            case SIGHUP:
                // 处理SIGHUP信号
                echo ("Caught SIGHUP...\n");
                break;
            case SIGUSR1:
                echo ("Caught SIGUSR1...\n");
                break;
            case SIGCHLD:
                $this->output("exiting...");
                break;
            default:
                // 处理所有其他信号
        }
    }

    /**
     * 打印数据到输出流.
     *
     * @param string $string 打印字符.
     *
     * @return $this
     */
    public function output($string)
    {
        echo $string, PHP_EOL;
        return $this;
    }

    /**
     * @var string
     */
    private $logfile;

    /**
     * 写日志.
     *
     * @return $this
     */
    public function log()
    {
        $args = func_get_args();
        $format = array_shift($args);
        foreach ($args as &$arg) {
            if (!is_scalar($arg)) {
                $arg = json_encode($arg);
            }
        }
        if (empty($args)) {
            $string = $format;
        } else {
            $string = call_user_func_array('sprintf', array_merge(array($format), $args));
        }
        $key = $this->currentKey;
        if (!is_scalar($key)) {
            $key = json_encode($key);
        }
        file_put_contents($this->logfile, sprintf("[PID: %d][S:%s] ", $this->currentPid, $key) . $string . PHP_EOL, FILE_APPEND);
        return $this;
    }

    /**
     * 停止执行.
     *
     * @return mixed
     */
    protected function stop()
    {
        die;
    }

}

/**
MultiProcessBase::instance()->scheduler(array(1,2,3,4,5,6,7,8,9,20))->processor(function($key, $value, MultiProcessBase $p) {
    sleep(1);
    $p->log("test");

})->bootstrap();
*/
