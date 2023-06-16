// src/lib.rs
#[no_mangle]
pub extern "C" fn fibonacci(n: libc::c_int) -> libc::c_int {
    if n == 1 { 1 }
    else if n == 2 { 1 }
    else { fibonacci(n-1) + fibonacci(n-2) }
}