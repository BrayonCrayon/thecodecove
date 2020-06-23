import React from 'react';
import {BrowserRouter, Link, Route, Switch} from 'react-router-dom';
import Home from './views/public_pages/Home';
import Login from './views/auth/Login';
import Register from './views/auth/Register';
// import NotFound from './views/NotFound/NotFound'// User is LoggedIn
// import PrivateRoute from './PrivateRoute'
// import Dashboard from './views/user/Dashboard/Dashboard';

const Router = props => (
    <Switch>
        {/*User might LogIn*/}
        <Route exact path='/' component={Home}/>  {/*User will LogIn*/}
        <Route path='/login' component={Login}/>
        <Route path='/register' component={Register}/>  {/* User is LoggedIn*/}
        {/*<PrivateRoute path='/dashboard' component={Dashboard}/>  /!*Page Not Found*!/*/}
        {/*<Route component={NotFound}/>*/}
    </Switch>
);

export default Router;
