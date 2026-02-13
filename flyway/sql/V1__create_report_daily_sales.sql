CREATE TABLE IF NOT EXISTS report_daily_sales (
    day date PRIMARY KEY,
    confirmed_orders integer NOT NULL,
    revenue_cents bigint NOT NULL,
    currency char(3) NOT NULL
);