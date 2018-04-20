import React, { Component } from 'react';
import classNames from 'classnames';
import { TransitionGroup, CSSTransition } from "react-transition-group";
import { Link } from "react-router-dom";

import './SignIn.scss';
import './Slide.css'
import FB_LOGO from './assets/facebook.png';
import TW_LOGO from './assets/twitter.png';
import INS_LOGO from './assets/instagram.png';

import InputForm from '../../component/InputForm';
import Checkbox from '../../component/Checkbox';


class Profile extends Component {

    constructor() {
        super();
        this.state = {
            username : '',
            fullName : '',
            profilePic : '',
            numPosts : 0,
            numFollowers : 0,
            numFollowing : 0
        }

    }
    fetchFirst(username) {
        let that = this;
        if (username) {
            fetch('http://localhost/InstagramScraper/examples/getAccountById.php?username=' + username+'&password=JzyMucun1996', {mode:'no-cors'})
                .then((response) => {
                    console.log(response)
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    }
    componentWillMount() {

        this.fetchFirst("jzy_tony");

    }

    render() {
        return (
            <div className={'SignIn'}>
                <div className={'background'}>
                    <div className={'panel'}>
                        <div className={'profile-container'}>
                            <div className={'profile-pic'}>
                                <img src={'https://images.pexels.com/photos/816708/pexels-photo-816708.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260'} />
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        );
    }
}

export default Profile;
