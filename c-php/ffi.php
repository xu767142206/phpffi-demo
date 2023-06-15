<?php

class SysInfo
{
    protected ?FFI $ffi;
    
    private static ?self $instance = null;
    
    private function __clone(): void
    {
    }
    
    private function __construct()
    {
        $this->initFFi();
    }
    
    public static function getInstance(): static
    {
        if (self::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    private function initFFi()
    {
        $this->ffi = FFI::cdef(<<<CTYPE
    struct SystemInfo {
    unsigned int OEMId;
    unsigned int numberOfProcessors;
    unsigned short processorArchitecture;
    unsigned int pageSize;
    unsigned int processorType;
    void *minimumApplicationAddress;
    void *maximumApplicationAddress;
    unsigned int activeProcessorMask;
    char *processorArchitectureName;
    char *cpuName;
};
struct MemoryInfo{
    long long totalMemory;
    long long availableMemory;
    long long usedMemory;
};

void getSystemInformation(struct SystemInfo *sysinfo);
void getMemoryStatus(struct MemoryInfo *memoryInfo);

CTYPE, "sysinfo.dll");
    }
    
    
    /**
     * 获取系统信息
     * @return SystemInformation
     */
    public function getSystemInformation(): SystemInformation
    {
        $systemInfo = $this->ffi->new("struct SystemInfo");
        $this->ffi->getSystemInformation(FFI::addr($systemInfo));
        
        $systemInformation                            = new SystemInformation();
        $systemInformation->OEMId                     = $systemInfo->OEMId;
        $systemInformation->numberOfProcessors        = $systemInfo->numberOfProcessors;
        $systemInformation->processorArchitecture     = $systemInfo->processorArchitecture;
        $systemInformation->pageSize                  = $systemInfo->pageSize;
        $systemInformation->processorType             = $systemInfo->processorType;
        $systemInformation->minimumApplicationAddress = FFI::alignof($systemInfo->minimumApplicationAddress);
        $systemInformation->maximumApplicationAddress = FFI::alignof($systemInfo->maximumApplicationAddress);
        $systemInformation->activeProcessorMask       = $systemInfo->activeProcessorMask;
        $systemInformation->processorArchitectureName = ffi::string($systemInfo->processorArchitectureName);
        $systemInformation->cpuName                   = ffi::string($systemInfo->cpuName);
        
        FFi::free(FFI::addr($systemInfo));
        
        return $systemInformation;
    }
    
    /**
     * 返回内存信息
     * @return MemoryStatus
     */
    public function getMemoryStatus(): MemoryStatus
    {
        $memoryInfo = $this->ffi->new("struct MemoryInfo");
        $this->ffi->getMemoryStatus(FFI::addr($memoryInfo));
        
        $memoryStatus                  = new MemoryStatus();
        $memoryStatus->totalMemory     = $memoryInfo->totalMemory;
        $memoryStatus->availableMemory = $memoryInfo->availableMemory;
        $memoryStatus->usedMemory      = $memoryInfo->usedMemory;
        
        FFi::free(FFI::addr($memoryInfo));
        
        return $memoryStatus;
    }
    
}

class SystemInformation
{
    public int $OEMId;
    public int $numberOfProcessors;
    public int $processorArchitecture;
    public int $pageSize;
    public int $processorType;
    public int $minimumApplicationAddress;
    public int $maximumApplicationAddress;
    public int $activeProcessorMask;
    public string $processorArchitectureName;
    public string $cpuName;
    
    public function toString(): string
    {
        return sprintf("cpu:%s\n系统架构:%s\ncpu核心:%d\n", $this->cpuName, $this->processorArchitectureName, $this->processorArchitecture);
    }
    
}


class MemoryStatus
{
    public int $totalMemory;
    public int $availableMemory;
    public int $usedMemory;
    
    public function toString(): string
    {
        return sprintf("总内存:%d mb\n空闲内存:%d mb\n使用内存:%d mb\n", $this->totalMemory >> 20, $this->availableMemory >> 20, $this->usedMemory >> 20);
    }
}

$sysInfo           = SysInfo::getInstance();
$memoryStatus      = $sysInfo->getMemoryStatus();
$systemInformation = $sysInfo->getSystemInformation();

printf("%s\n",$systemInformation->toString());
printf("%s\n",$memoryStatus->toString());




