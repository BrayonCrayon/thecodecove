import React from 'react';

const NavMenu = () => {

    return (
        <nav className="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div className="container">
                <a className="navbar-brand" href="">
                    The Code Cove
                </a>
                <button className="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="">
                    <span className="navbar-toggler-icon"></span>
                </button>

                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">

                    </ul>

                    <ul className="navbar-nav ml-auto">
                        {/*@guest*/}
                        <li className="nav-item">
                            <a className="nav-link" href="">Login</a>
                        </li>
                        {/*@if (Route::has('register'))*/}
                        <li className="nav-item">
                            <a className="nav-link" href="">Register</a>
                        </li>
                        {/*@endif*/}
                        {/*@else*/}
                        <li className="nav-item dropdown">
                            <a id="navbarDropdown" className="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {/*{{Auth::user()->name}} <span className="caret"></span>*/}
                            </a>

                            <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a className="dropdown-item">
                                    {/*{{__('Logout')}}*/}
                                </a>

                                <form id="logout-form" method="POST">
                                    {/*@csrf*/}
                                </form>
                            </div>
                        </li>
                        {/*@endguest*/}
                    </ul>
                </div>
            </div>
        </nav>
    );
};

export default NavMenu;
