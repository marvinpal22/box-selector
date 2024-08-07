<script setup>
import { ref, onMounted,reactive } from 'vue'
    const errors = ref({});
    const items = ref([
        {
            name: 'product 1',
            length: 60,
            width: 55,
            height: 50,
            weight: 50,
            quantity: 1
        },
        {
            name: 'product 2',
            length: 30,
            width: 25,
            height: 20,
            weight: 10,
            quantity: 1
        },
        {
            name: 'product 3',
            length: 20,
            width: 15,
            height: 10,
            weight: 5,
            quantity: 1
        },
        {
            name: 'product 4',
            length: 50,
            width: 45,
            height: 40,
            weight: 30,
            quantity: 1
        },
        {
            name: 'product 5',
            length: 40,
            width: 35,
            height: 30,
            weight: 20,
            quantity: 1
        },
    ]);

    const results = ref([]);

    const addRow = () =>{
        let name = 'Product '+(items.value.length+1);
        items.value.push({
            name: name,
            length: null,
            width: null,
            height: null,
            weight: null,
            quantity: null
        })
    }

    const removeRow = (index) =>{
        items.value.splice(index, 1);
    }

    const submit = async () => {
        axios.post('/pack-items', {product:array()}).then((response)=>{
            results.value = response.data;
            console.log(response);
        }).catch((error) =>{
            errors.value = error?.response?.data.errors
        })
    }

    const array = () => {
        let array = [];
        items.value.forEach((element) => {
            for (let a = 0; a < element.quantity; a++) {
                // Runs 5 times, with values of step 0 through 4.
                array.push({
                    name: element.name,
                    length: element.length,
                    width: element.width,
                    height: element.height,
                    weight: element.weight,
                    quantity: 1
                })
            }
        });

        return array;
    }

</script>
<template>
    <div class="container border-thin px-5 py-5 mb-15">
        <div class="d-flex justify-center pb-5">
            <div style="font-size: 2em; ">
                <b class="title">PRODUCT BOX SELECTOR</b>
            </div>
        </div>
        <hr>
        <VForm @submit.prevent="submit">
            <div class="d-flex justify-end bottom-0 right-0 pt-5">
                <v-btn @click="addRow()" style="background-color: #ffb32f; color:white;">Add row</v-btn>
            </div>
            <v-table style="background-color: #f5f5f5;;">
                <thead>
                <tr>
                    <th class="text-left">
                        Name
                    </th>
                    <th class="text-left">
                        Dimension
                    </th>
                    <th class="text-left">
                        Weight (per pc)
                    </th>
                    <th class="text-left">
                        Quantity
                    </th>
                    <th class="text-left">
                        
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(item,index) in items"
                >
                    <td><v-label>{{ item.name }}</v-label></td>
                    <td class="pt-5">
                        <div class="d-flex gap-4">
                            <div>
                                <div style="color: red; font-size: 13px;">
                                    <div v-if="errors[`product.${index}.length`]">This field is required.</div>
                                </div>
                                <v-text-field v-model="item.length" type="number" label="length (cm)" variant="outlined" style="inline-size: 180px;"/>
                            </div>
                            <div>
                                <div style="color: red; font-size: 13px;">
                                    <div v-if="errors[`product.${index}.width`]">This field is required.</div>
                                </div>
                                <v-text-field v-model="item.width" type="number" label="width (cm)" variant="outlined" style="inline-size: 180px;"/>
                            </div>
                            <div>
                                <div style="color: red; font-size: 13px;" v-if="errors[`product.${index}.height`]">This field is required</div>
                                <v-text-field v-model="item.height"  type="number" label="height (cm)" variant="outlined" style="inline-size: 180px;"/>
                            </div>
                        </div>
                    </td>
                    <td class="pt-5">
                        <div style="color: red; font-size: 13px;">
                            <div v-if="errors[`product.${index}.weight`]">This field is required.</div>
                        </div>
                        <v-text-field v-model="item.weight" type="number" label="weight (kg)" variant="outlined"></v-text-field>
                    </td>
                    <td class="pt-5">
                        <div style="color: red; font-size: 13px;" v-if="errors[`product.${index}.quantity`]">This field is required</div>
                        <v-text-field v-model="item.quantity" type="number" label="quantity" variant="outlined"></v-text-field>
                    </td>
                    <td><v-btn icon="" color="error" size="30" @click="removeRow(index)">x</v-btn></td>
                </tr>
                </tbody>
            </v-table>
            <hr>
            <div class="d-flex justify-end bottom-0 right-0 pt-5">
                <v-btn color="primary" type="submit" >Submit</v-btn>
            </div>
        </VForm>
        <hr class="mt-5 pb-5">
        <div>
            <div style="font-size: 1.4em; font-weight: bold; color: #666363;">
                RESULTS:
            </div>
            <div v-for="(product) in results" class="mt-3" style="border-radius: 11px; border:solid 1px; border-color: #DCDCDC; padding-left: 20px; padding-top:10px; padding-bottom: 10px;">
                <div v-for="(item) in product">
                    <div>
                        {{ item }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>

@import url('https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Bungee&family=Calistoga&family=Chelsea+Market&family=Chicle&family=Limelight&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Rubik+Bubbles&family=Zain:wght@200;300;400;700;800;900&display=swap');
body{
    background-color: #f5f5f5;
}
.container{
    font-family: "Montserrat", sans-serif;
    max-width: 1300px;
    margin-top: 5%;
    margin-left: auto;
    margin-right: auto;
    border-radius: 10px;
    border-width: 1em;
}

.title{
    color: #1867C0;
}
.add-row-btn{
    background-color:#0a0a23;
}
</style>