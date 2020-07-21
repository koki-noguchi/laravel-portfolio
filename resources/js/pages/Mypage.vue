<template>
    <div>
        <History></History>
        <UserProfile></UserProfile>
    </div>
</template>

<script>
import History from '../components/History.vue'
import UserProfile from "../components/UserProfile.vue";
export default {
    components: {
        History,
        UserProfile
    }
}
</script>