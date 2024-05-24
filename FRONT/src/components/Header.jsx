import { useState } from 'react'
import Nav from './Nav'
import { Link } from "react-router-dom";


function Header() {
  const [count, setCount] = useState(0)

  return (
    <div>
      
<header className="bg-pcs-200 sticky top-0 z-[20]">
<nav className="bg-pcs-200 border-gray-200 dark:bg-gray-900 w-full">
  <div className="  w-full flex flex-wrap items-center justify-around p-1">
    <a href="/" className="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="/public/images/fullDay.png" className="h-8 logo" alt="PCSLogo" />
    </a>
    <Nav/>
    <button data-collapse-toggle="navbar-default" type="button" className="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden  focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400  dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
        <span className="sr-only">Open main menu</span>
        <svg className="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div className="hidden w-full md:block md:w-auto" id="navbar-default">
      
    </div>
  </div>
</nav>
</header>

    </div>
  )
}
export default Header