import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Route} from 'react-router-dom';
import Router from './Router';
import NavMenu from "./views/layouts/NavMenu";

const Index = () => {

    return (
        <BrowserRouter>
            <NavMenu/>
            <Route component={Router} />
        </BrowserRouter>
    );
};

export default Index;

ReactDOM.render(<Index/>, document.getElementById('index'));
