<script setup>
    import DateRangePicker from './DateRangePicker.vue'
    import selectRoom from './selectRoom.vue'
    import { ref } from 'vue'
    const value1 = ref(true)
    
</script>
<template>
    <div class="container">
        <div class="wrapper">
            <div id="title" style="text-align: center;">
                <p>A PLACE TO CALL HOME ON YOUR NEXT ADVENTURE</p>
            </div>
            <div class="content">
                <div id="inputSearch">
                    <div class="input-container">
                        <input type="text" placeholder="Enter Your Destination or Property">
                        <i id="search" class="pi pi-search"></i>
                    </div>
                </div>
                <div class="datePickerContainer">
                    <DateRangePicker/> 
                </div>
                <div class="selectRoom">
                    <div style="margin-top: 20px;">
                        <selectRoom/>
                    </div>
                    <div style="margin-top: 40px; margin-left: 10px;">
                        Traveling with pet?
                        <el-switch v-model="value1" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .container{
        display: flex;
        justify-content: center;
        align-items: center;
        width:100vw;
        height: 100vh;
    }
    p{
        font-size: 3vh;
        color:white;
    }
    .wrapper{
        padding:50px;
        border-radius: 36px;
        background: #0A2647;
        box-shadow:  23px 23px 50px #a4bbda,
                    -23px -23px 50px #89acd3;
    }
    #inputSearch{
        display: flex;
        justify-content: center;
        margin-top:20px;
        margin-bottom:20px;
    }
    .input-container {
        position: relative;
        width: 100%;
    }
    .input-container input {
        width: 100%;
        padding: 16px;
        padding-left: 30px; 
        border-radius: 12px;
        border: none;
    }
    .input-container i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        color: #999;
    }
    .selectRoom{
        display:flex;
        color:white;
    }
</style>