<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <section class="relative">
        <nav class="flex items-center p-8 bg-gray-700 mb-3">
          <div class="w-full xl:w-auto px-2 xl:mr-12">
            <div class="flex items-center justify-between">
              <a class="inline-flex items-center h-8" href="#">
                <img src="trizzle-assets/logos/trizzle-logo.svg" alt="">
              </a>
              <div class="xl:hidden">
                <button class="navbar-burger text-gray-400 hover:text-gray-300 focus:outline-none">
                  <svg width="20" height="12" viewbox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <title>Mobile menu</title>
                    <path d="M1 2H19C19.2652 2 19.5196 1.89464 19.7071 1.70711C19.8946 1.51957 20 1.26522 20 1C20 0.734784 19.8946 0.48043 19.7071 0.292893C19.5196 0.105357 19.2652 0 19 0H1C0.734784 0 0.48043 0.105357 0.292893 0.292893C0.105357 0.48043 0 0.734784 0 1C0 1.26522 0.105357 1.51957 0.292893 1.70711C0.48043 1.89464 0.734784 2 1 2ZM19 10H1C0.734784 10 0.48043 10.1054 0.292893 10.2929C0.105357 10.4804 0 10.7348 0 11C0 11.2652 0.105357 11.5196 0.292893 11.7071C0.48043 11.8946 0.734784 12 1 12H19C19.2652 12 19.5196 11.8946 19.7071 11.7071C19.8946 11.5196 20 11.2652 20 11C20 10.7348 19.8946 10.4804 19.7071 10.2929C19.5196 10.1054 19.2652 10 19 10ZM19 5H1C0.734784 5 0.48043 5.10536 0.292893 5.29289C0.105357 5.48043 0 5.73478 0 6C0 6.26522 0.105357 6.51957 0.292893 6.70711C0.48043 6.89464 0.734784 7 1 7H19C19.2652 7 19.5196 6.89464 19.7071 6.70711C19.8946 6.51957 20 6.26522 20 6C20 5.73478 19.8946 5.48043 19.7071 5.29289C19.5196 5.10536 19.2652 5 19 5Z" fill="currentColor"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <div class="hidden xl:block w-full md:w-auto px-2 mr-auto">
            <ul class="flex items-center">
              <li class="mr-10"><a class="inline-block text-sm font-semibold text-gray-300 hover:text-gray-200" href="#">Overview</a></li>
              <li class="mr-10"><a class="inline-block text-sm font-semibold text-gray-300 hover:text-gray-200" href="#">Analytics</a></li>
              <li class="mr-10"><a class="inline-block text-sm font-semibold text-gray-300 hover:text-gray-200" href="#">Products</a></li>
              <li class="mr-10">
                <a class="inline-flex items-center text-sm font-semibold text-gray-300 hover:text-gray-200" href="#">
                  <span>Orders</span>
                  <div class="flex w-5 h-5 ml-2 items-center justify-center text-xs text-white bg-blue-500 rounded-full">4</div>
                </a>
              </li>
              <li><a class="inline-block text-sm font-semibold text-gray-300 hover:text-gray-200" href="#">Customers</a></li>
            </ul>
          </div>
          <div class="hidden xl:block w-full md:w-auto px-2">
            <div class="flex flex-wrap items-center -mb-2">
              <a class="inline-block mb-2 mr-6 text-gray-400 hover:text-gray-300" href="#">
                <svg width="20" height="16" viewbox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M17 0H3C2.20435 0 1.44129 0.316071 0.87868 0.87868C0.316071 1.44129 0 2.20435 0 3V13C0 13.7956 0.316071 14.5587 0.87868 15.1213C1.44129 15.6839 2.20435 16 3 16H17C17.7956 16 18.5587 15.6839 19.1213 15.1213C19.6839 14.5587 20 13.7956 20 13V3C20 2.20435 19.6839 1.44129 19.1213 0.87868C18.5587 0.316071 17.7956 0 17 0ZM3 2H17C17.2652 2 17.5196 2.10536 17.7071 2.29289C17.8946 2.48043 18 2.73478 18 3L10 7.88L2 3C2 2.73478 2.10536 2.48043 2.29289 2.29289C2.48043 2.10536 2.73478 2 3 2ZM18 13C18 13.2652 17.8946 13.5196 17.7071 13.7071C17.5196 13.8946 17.2652 14 17 14H3C2.73478 14 2.48043 13.8946 2.29289 13.7071C2.10536 13.5196 2 13.2652 2 13V5.28L9.48 9.85C9.63202 9.93777 9.80446 9.98397 9.98 9.98397C10.1555 9.98397 10.328 9.93777 10.48 9.85L18 5.28V13Z" fill="currentColor"></path>
                </svg>
              </a>
              <a class="inline-block mb-2 mr-8 text-gray-400 hover:text-gray-300" href="#">
                <svg width="16" height="20" viewbox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14 11.18V8C13.9986 6.58312 13.4958 5.21247 12.5806 4.13077C11.6655 3.04908 10.3971 2.32615 9 2.09V1C9 0.734784 8.89464 0.48043 8.70711 0.292893C8.51957 0.105357 8.26522 0 8 0C7.73478 0 7.48043 0.105357 7.29289 0.292893C7.10536 0.48043 7 0.734784 7 1V2.09C5.60294 2.32615 4.33452 3.04908 3.41939 4.13077C2.50425 5.21247 2.00144 6.58312 2 8V11.18C1.41645 11.3863 0.910998 11.7681 0.552938 12.2729C0.194879 12.7778 0.00173951 13.3811 0 14V16C0 16.2652 0.105357 16.5196 0.292893 16.7071C0.48043 16.8946 0.734784 17 1 17H4.14C4.37028 17.8474 4.873 18.5954 5.5706 19.1287C6.26819 19.6621 7.1219 19.951 8 19.951C8.8781 19.951 9.73181 19.6621 10.4294 19.1287C11.127 18.5954 11.6297 17.8474 11.86 17H15C15.2652 17 15.5196 16.8946 15.7071 16.7071C15.8946 16.5196 16 16.2652 16 16V14C15.9983 13.3811 15.8051 12.7778 15.4471 12.2729C15.089 11.7681 14.5835 11.3863 14 11.18ZM4 8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4C9.06087 4 10.0783 4.42143 10.8284 5.17157C11.5786 5.92172 12 6.93913 12 8V11H4V8ZM8 18C7.65097 17.9979 7.30857 17.9045 7.00683 17.7291C6.70509 17.5536 6.45451 17.3023 6.28 17H9.72C9.54549 17.3023 9.29491 17.5536 8.99317 17.7291C8.69143 17.9045 8.34903 17.9979 8 18ZM14 15H2V14C2 13.7348 2.10536 13.4804 2.29289 13.2929C2.48043 13.1054 2.73478 13 3 13H13C13.2652 13 13.5196 13.1054 13.7071 13.2929C13.8946 13.4804 14 13.7348 14 14V15Z" fill="currentColor"></path>
                </svg>
              </a>
              <a class="group inline-flex mb-2 items-center" href="#">
                <img class="h-8 w-8 mr-3 rounded-full object-cover" src="trizzle-assets/images/avatar-men.png" alt="">
                <h4 class="text-white font-extrabold tracking-wide mr-4">John Doe</h4>
                <span class="text-gray-400 group-hover:text-gray-300">
                  <svg width="10" height="6" viewbox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </span>
              </a>
            </div>
          </div>
        </nav>
        <div class="hidden navbar-menu fixed top-0 left-0 bottom-0 w-3/4 lg:w-80 sm:max-w-xs z-50">
          <div class="navbar-backdrop fixed top-0 left-0 w-full h-full bg-gray-800 opacity-50"></div>
          <nav class="relative flex flex-col p-8 h-full w-full bg-gray-700 overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
              <a class="inline-flex items-center" href="#">
                <img class="h-10" src="trizzle-assets/logos/trizzle-logo.svg" alt="">
              </a>
              <button class="navbar-close">
                <svg class="h-6 w-6 text-gray-400 cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            <ul class="mb-10">
              <li class="mb-10"><a class="inline-block text-lg font-semibold text-gray-300 hover:text-gray-200" href="#">Overview</a></li>
              <li class="mb-10"><a class="inline-block text-lg font-semibold text-gray-300 hover:text-gray-200" href="#">Analytics</a></li>
              <li class="mb-10"><a class="inline-block text-lg font-semibold text-gray-300 hover:text-gray-200" href="#">Products</a></li>
              <li class="mb-10">
                <a class="inline-flex w-full items-center justify-between text-lg font-semibold text-gray-300 hover:text-gray-200" href="#">
                  <span>Orders</span>
                  <div class="flex w-5 h-5 ml-2 items-center justify-center text-xs text-white bg-blue-500 rounded-full">4</div>
                </a>
              </li>
              <li><a class="inline-block text-lg font-semibold text-gray-300 hover:text-gray-200" href="#">Customers</a></li>
            </ul>
            <a class="flex mt-auto items-center justify-between" href="#">
              <div class="flex items-center">
                <img class="h-8 w-8 mr-3 rounded-full" src="trizzle-assets/images/avatar-men-2.png" alt="">
                <h5 class="leading-none font-semibold text-gray-100">John Doe</h5>
              </div>
              <svg width="10" height="6" viewbox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L5 5L9 1" stroke="#3D485B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </a>
          </nav>
        </div>
      </section>
</body>
</html>
