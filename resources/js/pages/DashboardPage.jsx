import React, { useEffect, useState } from 'react';
import { ICONS } from '../components/IconMap';

export default function DashboardPage({ user, onLogout, authApi }) {
    const [month, setMonth] = useState(() => new Date().toISOString().slice(0, 7));
    const [report, setReport] = useState({ visits: 0, contacts: 0, series: { labels: [], visits: [], contacts: [] } });
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (!authApi) return;
        setLoading(true);
        authApi.get(`/reports/monthly?month=${month}`)
            .then((res) => setReport({
                visits: res.data.visits ?? 0,
                contacts: res.data.contacts ?? 0,
                series: res.data.series || { labels: [], visits: [], contacts: [] },
            }))
            .finally(() => setLoading(false));
    }, [authApi, month]);

    const buildPolyline = (values, width, height, padding) => {
        if (!values.length) return '';
        const max = Math.max(1, ...values);
        const stepX = (width - padding * 2) / Math.max(values.length - 1, 1);
        return values
            .map((value, index) => {
                const x = padding + index * stepX;
                const y = height - padding - (value / max) * (height - padding * 2);
                return `${x},${y}`;
            })
            .join(' ');
    };

    const buildBars = (values, width, height, padding) => {
        const max = Math.max(1, ...values);
        const barWidth = (width - padding * 2) / Math.max(values.length, 1);
        return values.map((value, index) => {
            const x = padding + index * barWidth + barWidth * 0.15;
            const barW = barWidth * 0.7;
            const barH = (value / max) * (height - padding * 2);
            const y = height - padding - barH;
            return { x, y, width: barW, height: barH };
        });
    };

    return (
        <div className="content">
            <div className="page-header">
                <h1 className="with-icon">
                    <span className="icon">{ICONS.dashboard}</span>
                    Dashboard CMS
                </h1>
                <p>Overview of visits and contact requests.</p>
            </div>

            <div className="dashboard-hero">
                <div>
                    <p className="dashboard-kicker">Welcome back</p>
                    <h2>{user?.name || 'User'}</h2>
                    <span className="dashboard-subtitle">Role: {user?.role || '-'}</span>
                </div>
                <div className="dashboard-controls">
                    <label className="muted">Month</label>
                    <input
                        type="month"
                        className="form-control"
                        value={month}
                        onChange={(e) => setMonth(e.target.value)}
                    />
                </div>
            </div>

            <div className="report-grid">
                <div className="report-card">
                    <div className="report-card-icon">{ICONS.dashboard}</div>
                    <div>
                        <p className="report-label">Visitors</p>
                        <h3>{loading ? 'Loading...' : report.visits}</h3>
                        <span className="report-note">Unique sessions this month</span>
                    </div>
                </div>
                <div className="report-card">
                    <div className="report-card-icon">{ICONS.contacts}</div>
                    <div>
                        <p className="report-label">Contact Requests</p>
                        <h3>{loading ? 'Loading...' : report.contacts}</h3>
                        <span className="report-note">Messages received</span>
                    </div>
                </div>
            </div>

            <div className="panel">
                <div className="section-toolbar">
                    <h3>Trends</h3>
                    <span className="muted">Daily visitors vs contact requests</span>
                </div>
                <div className="report-chart">
                    <svg viewBox="0 0 640 240" role="img" aria-label="Monthly trend chart">
                        <defs>
                            <linearGradient id="visitorsFill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stopColor="rgba(31, 157, 85, 0.35)" />
                                <stop offset="100%" stopColor="rgba(31, 157, 85, 0.05)" />
                            </linearGradient>
                        </defs>
                        <rect x="0" y="0" width="640" height="240" rx="16" fill="transparent" />
                        {buildBars(report.series.visits, 640, 240, 32).map((bar, index) => (
                            <rect
                                key={`bar-${index}`}
                                x={bar.x}
                                y={bar.y}
                                width={bar.width}
                                height={bar.height}
                                fill="url(#visitorsFill)"
                                rx="6"
                            />
                        ))}
                        <polyline
                            fill="none"
                            stroke="#c9a227"
                            strokeWidth="3"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            points={buildPolyline(report.series.contacts, 640, 240, 32)}
                        />
                    </svg>
                    <div className="report-legend">
                        <span><span className="legend-dot legend-visitors"></span>Visitors</span>
                        <span><span className="legend-dot legend-contacts"></span>Contacts</span>
                    </div>
                </div>
            </div>

            <div className="panel">
                <div className="actions">
                    <button className="ghost" onClick={onLogout}>Logout</button>
                </div>
            </div>
        </div>
    );
}
