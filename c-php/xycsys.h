//
// Created by NINGMEI on 2023/6/15.
//

#ifndef SWOOLE_CLI_V5_0_3_CYGWIN_X64_XYCSYS_H
#define SWOOLE_CLI_V5_0_3_CYGWIN_X64_XYCSYS_H

#endif //SWOOLE_CLI_V5_0_3_CYGWIN_X64_XYCSYS_H

//系统信息
typedef struct {
    unsigned int OEMId;
    //CPU核心
    unsigned int numberOfProcessors;
//    unsigned int physical;

    unsigned short processorArchitecture;
    //页码数
    unsigned int pageSize;
    unsigned int processorType;
    void *minimumApplicationAddress;
    void *maximumApplicationAddress;
    unsigned int activeProcessorMask;
    //架构
    char *processorArchitectureName;
    //名称
    char *cpuName;
} SystemInfo;

//内存信息
typedef struct {
    long long totalMemory;
    long long availableMemory;
    long long usedMemory;
} MemoryInfo;


// 设置系统信息
void getSystemInformation(SystemInfo *sysinfo);

// getSystemInformation 获取内存信息
void getMemoryStatus();
