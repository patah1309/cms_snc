import React from 'react';

export const ICONS = {
    home: <i className="fa-solid fa-house"></i>,
    about: <i className="fa-solid fa-user-tie"></i>,
    services: <i className="fa-solid fa-briefcase"></i>,
    news: <i className="fa-regular fa-newspaper"></i>,
    team: <i className="fa-solid fa-people-group"></i>,
    contacts: <i className="fa-solid fa-envelope-open-text"></i>,
    settings: <i className="fa-solid fa-gear"></i>,
    menus: <i className="fa-solid fa-bars"></i>,
    users: <i className="fa-solid fa-users"></i>,
    access: <i className="fa-solid fa-user-shield"></i>,
    dashboard: <i className="fa-solid fa-layer-group"></i>,
    collapse: <i className="fa-solid fa-bars"></i>,
};

export const getIcon = (key) => ICONS[key] || ICONS.dashboard;
