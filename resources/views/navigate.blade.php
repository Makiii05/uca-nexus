<x-layout>
    <div class="flex self-center m-auto justify-center w-full p-6"> <!--center this x-->
    
        <div class="w-full">
            <div class="text-center space-y-2 mb-12">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    DEPARTMENTS
                </h2>
                <p class="text-slate-500 text-lg">Please select your department to continue to login.</p>
            </div>
    
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-8 vw-100">

                <a href="{{ route('admin.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-slate-200">
                    <div class="mb-4 p-4 bg-slate-50 rounded-2xl text-slate-600 group-hover:bg-slate-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">ADMIN</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">System Settings</p>
                </a>
                
                <a href="{{ route('registrar.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-indigo-200">
                    <div class="mb-4 p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">REGISTRAR</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">List & Records</p>
                </a>
    
                <a href="{{ route('accounting.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-emerald-200">
                    <div class="mb-4 p-4 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
    
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">ACCOUNTING</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">Fees & Payments</p>
                </a>
    
                <a href="{{ route('admission.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-orange-200">
                    <div class="mb-4 p-4 bg-orange-50 rounded-2xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">ADMISSIONS</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">New Enrollees</p>
                </a>
    
                <a href="{{ route('applicant.form') }}" target="_blank" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-cyan-200">
                    <div class="mb-4 p-4 bg-cyan-50 rounded-2xl text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>

                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">APPLICATION</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">Application Form</p>
                </a>
    
                <a href="{{ route('department.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-purple-200">
                    <div class="mb-4 p-4 bg-purple-50 rounded-2xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 0A.75.75 0 0 1 7.5 6h9a.75.75 0 0 1 .75.75m-.75 0V4.5" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">DEPARTMENT</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">Department Portal</p>
                </a>

                <a href="{{ route('student_portal.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-sky-200">
                    <div class="mb-4 p-4 bg-sky-50 rounded-2xl text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">STUDENT</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">Student Portal</p>
                </a>

                <a href="{{ route('teacher_portal.login') }}" class="group relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-yellow-200">
                    <div class="mb-4 p-4 bg-yellow-50 rounded-2xl text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-wide">TEACHER</span>
                    <p class="mt-2 text-sm text-slate-400 text-center">Teacher Portal</p>
                </a>
    
            </div>
    
            <p class="mt-16 text-center text-slate-400 text-sm">
                &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
            </p>
        </div>
    
    </div>
</x-layout>