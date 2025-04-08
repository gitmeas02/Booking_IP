<script setup>
import datePicker from './datePicker.vue'
import { ref } from 'vue'
import { Search } from '@element-plus/icons-vue'
import {Edit} from '@element-plus/icons-vue'
import selectRoom from './selectRoom.vue'


const input2 = ref('')
const value = ref('')
</script>
<template>
    <div class="container">
        <div class="cardwrapper">
            <div>
                <h2>A PLACE TO CALL HOME ON YOUR NEXT ADVENTURE</h2>
            </div>
            <div class="flex gap-4 mb-4 items-center">
                <el-input
                v-model="input2"
                style="width: 80%"
                size="large"
                placeholder="Enter The Destionation or Property"
                :suffix-icon="Search"
                />
            </div>
            <div class="date-select">
                <div id="datepicker">
                    <datePicker/>
                </div>
                <div id="roomselect">
                    <selectRoom/>
                </div>
                
            </div>
            <div>
                <el-button type="primary" size="large"> Search
                    <el-icon class="el-icon--right">
                        <Search />
                    </el-icon>
                </el-button>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .container{
        display:flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    .cardwrapper{
        padding:10px;
        color:white;
        text-align: center;
        line-height: 2;
        width:50%;
        height: 45%;
        border-radius: 40px;
        background: #0A2647;
        box-shadow:  10px 10px 44px #b5b5b5,
                    -10px -10px 44px #ffffff;
    }
    h2{
        line-height: 100px;
    }
    .date-select{
        display:flex;
        position:relative;
        justify-content: center;
        flex-direction: row;
        gap:36px;
    }
    #roomselect{
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top: 24px;
    }

</style>