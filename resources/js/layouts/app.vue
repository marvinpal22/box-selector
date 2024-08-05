<script setup>
import { ref, onMounted } from 'vue'
    const items = ref([{
        name: 'product 1',
        length: 60,
        width: 55,
        height: 50,
        weight: 50,
        quantity: 1
    },
    {
        name: 'product 2',
        length: 20,
        width: 15,
        height: 10,
        weight: 5,
        quantity: 1
    },
]);

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

    const submit = () => {
        axios.post('/pack-items', {product:array()}).then((response)=>{
            console.log(response);
        }).catch((error) =>{
            console.log(error);
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
    <div class="container border-thin px-5 py-5">
        <div class="d-flex justify-center pb-5">
            <VLabel style="font-size: 2em; ">
                PRODUCT BOX SELECTOR
            </VLabel>
        </div>
        <VForm @submit.prevent="submit">
            <v-table>
                <thead>
                <tr>
                    <th class="text-left">
                        Name
                    </th>
                    <th class="text-left">
                        Dimension
                    </th>
                    <th class="text-left">
                        Weight (per pcs)
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
                    <td class="pt-5"><v-label>{{ item.name }}</v-label></td>
                    <td class="pt-5">
                        <div class="d-flex gap-4">
                            <v-text-field v-model="item.length" type="number" label="length (cm)" variant="outlined" style="inline-size: 100px;"></v-text-field>
                            <v-text-field v-model="item.width" type="number" label="width (cm)" variant="outlined" style="inline-size: 100px;"></v-text-field>
                            <v-text-field v-model="item.height"  type="number" label="height (cm)" variant="outlined" style="inline-size: 100px;"></v-text-field>
                        </div>
                    </td>
                    <td class="pt-5"><v-text-field v-model="item.weight" type="number" label="weight (kg)" variant="outlined"></v-text-field></td>
                    <td class="pt-5"><v-text-field v-model="item.quantity" type="number" label="quantity" variant="outlined"></v-text-field></td>
                    <td class="pt-5"><v-btn icon="" color="error" @click="removeRow(index)">x</v-btn></td>
                </tr>
                </tbody>
            </v-table>
            <div class="d-flex justify-end bottom-0 right-0 pb-5">
                <v-btn color="success" @click="addRow()">Add row</v-btn>
            </div>
            <hr>
            <div class="d-flex justify-end bottom-0 right-0 pt-5">
                <v-btn color="primary" type="submit" >Submit</v-btn>
            </div>
        </VForm>


    </div>
</template>
<style>
.container{
    max-width: 1000px;
    margin-top: 5%;
    margin-left: auto;
    margin-right: auto;
    border-radius: 10px;
}
</style>