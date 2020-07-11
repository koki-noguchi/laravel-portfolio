<template>
    <nav class="navbar">
        <RouterLink class="navbar-brand" to="/">
            Message For You
        </RouterLink>
        <div class="navbar-menu">
            <span class="navbar-item">
                <RouterLink class="button button--link" to="/">
                    ホーム
                </RouterLink>
            </span>
            <span class="navbar-item">
                <RouterLink class="button button--link" to="/register">
                    新規登録
                </RouterLink>
            </span>
            <span class="navbar-item">
                <RouterLink class="button button--link" to="/login">
                    ログイン
                </RouterLink>
            </span>
        </div>
    </nav>
</template>