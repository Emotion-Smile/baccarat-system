<template>
	<app-layout title="API Tokens">
		<div class="flex-1 w-full p-4 m-auto rounded bg-fill xl:max-w-7xl xl:h-4/5">
			<div class="flex justify-between">
				<h1 class="text-xl text-wala">Report</h1>
				<div>
					<label for="location" class="block text-sm font-medium text-gray-700">Location</label>
					<select id="location" name="location" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md sm:w-36 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
						<option>USA</option>
						<option selected="">Canada</option>
						<option>EU</option>
					</select>
				</div>
			</div>
			<div class="flex flex-col mt-3">
				<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
							<table class="min-w-full divide-y divide-gray-200">
								<thead class="bg-black">
									<tr>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											label
										</th>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											labele
										</th>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											labell
										</th>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											label
										</th>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											label
										</th>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											label
										</th>
										<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase">
											label
										</th>
										<th scope="col" class="relative px-6 py-3">
											<span class="sr-only">Edit</span>
										</th>
									</tr>
								</thead>
								
								<!-- <tbody>
									<tr v-for="(person, personIdx) in people" :key="person.email" :class="personIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
										<td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
											{{ person.name }}
										</td>
										<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
											{{ person.title }}
										</td>
										<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
											{{ person.email }}
										</td>
										<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
											{{ person.role }}
										</td>
										<td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
											<a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
										</td>
									</tr>
								</tbody> -->
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</app-layout>
</template>

<script>
	import {defineComponent} from 'vue'
	import AppLayout from '@/Layouts/AppLayout.vue'

	const people = [
		{ name: 'Jane Cooper', title: 'Regional Paradigm Technician', role: 'Admin', email: 'jane.cooper@example.com' },
		{ name: 'Cody Fisher', title: 'Product Directives Officer', role: 'Owner', email: 'cody.fisher@example.com' },
		// More people...
	]
	export default defineComponent ({
		name: "Report",
		props: [],
		components: {
			AppLayout,
		},
		setup() {
			return {
				people,
			}
		},
	})
</script>