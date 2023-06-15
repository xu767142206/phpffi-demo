#include <stdio.h>
#include <windows.h>
#include <intrin.h>

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

struct MemoryInfo {
    long long totalMemory;
    long long availableMemory;
    long long usedMemory;
};

// 设置系统信息
void getSystemInformation(struct SystemInfo *sysinfo);

// getSystemInformation 获取内存信息
void getMemoryStatus(struct MemoryInfo *memoryInfo);


// getSystemInformation 获取系统信息
void getSystemInformation(struct SystemInfo *sysinfo) {

    int CPUInfo[4];
//    char CPUBrandString[0x40];
    SYSTEM_INFO systemInfo;

    GetSystemInfo(&systemInfo);
    sysinfo->OEMId = systemInfo.dwOemId;
    sysinfo->numberOfProcessors = systemInfo.dwNumberOfProcessors;
    sysinfo->processorArchitecture = systemInfo.wProcessorArchitecture;
    sysinfo->pageSize = systemInfo.dwPageSize;
    sysinfo->processorType = systemInfo.dwProcessorType;
    sysinfo->minimumApplicationAddress = systemInfo.lpMinimumApplicationAddress;
    sysinfo->maximumApplicationAddress = systemInfo.lpMaximumApplicationAddress;
    sysinfo->activeProcessorMask = systemInfo.dwActiveProcessorMask;


    //设置cpu架构
    switch (sysinfo->processorArchitecture) {
        case PROCESSOR_ARCHITECTURE_INTEL:
            sysinfo->processorArchitectureName = "Intel x86";
            break;
        case PROCESSOR_ARCHITECTURE_AMD64:
            sysinfo->processorArchitectureName = "AMD x64";
            break;
        case PROCESSOR_ARCHITECTURE_ARM:
            sysinfo->processorArchitectureName = "ARM";
            break;
        default:
            sysinfo->processorArchitectureName = "Unknown";
    }

    //cpu名称
    sysinfo->cpuName = (char *) malloc(sizeof(char) * 0x40);

    __cpuid(CPUInfo, 0x80000002);
    memcpy(sysinfo->cpuName, CPUInfo, sizeof(CPUInfo));
    __cpuid(CPUInfo, 0x80000003);
    memcpy(sysinfo->cpuName + 16, CPUInfo, sizeof(CPUInfo));
    __cpuid(CPUInfo, 0x80000004);
    memcpy(sysinfo->cpuName + 32, CPUInfo, sizeof(CPUInfo));


}

// getSystemInformation 获取内存信息
void getMemoryStatus(struct MemoryInfo *memoryInfo) {

    MEMORYSTATUSEX statex;
    statex.dwLength = sizeof(statex);
    GlobalMemoryStatusEx(&statex);

    memoryInfo->totalMemory = statex.ullTotalPhys;
    memoryInfo->availableMemory = statex.ullAvailPhys;
    memoryInfo->usedMemory = statex.ullTotalPhys - statex.ullAvailPhys;

}


int main() {
    struct SystemInfo sysinfo;

    getSystemInformation(&sysinfo);

    printf("%s\n", sysinfo.cpuName);
    printf("%p\n", sysinfo.cpuName);
    return 0;
}
