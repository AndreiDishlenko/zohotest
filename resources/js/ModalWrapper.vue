<template>
	<div :id="instance" class="modal top-0 fade"  data-bs-backdrop="static" tabindex='-1' :data-hash="'#'+instance">
		<div class="modal-dialog" :class="size">
			<div class="modal-content">

				<slot></slot>

			</div>
		</div>
	</div>
</template>

<script>
    import { Modal } from 'bootstrap';

	export default {
		props: {
			'instance': {
				type: String,
				required: false,
				// default: randomString(5)
			},
			'size': {
				type: String,
				required: false,
				default: 'modal-xl'
			},
			'esc_close': {
				type: Boolean,
				required: false,
				dafult: false
			}
		},
        data: function() {
            return {
				modalform: '',
            }
        },
        mounted() {
			// console.log('mounted', '#'+this.instance, $('#'+this.instance));			
			this.modalform = new Modal('#'+this.instance, {
                focus: true,
                keyboard: this.esc_close ? true : false
            });
			let dom_modal = document.getElementById(this.instance);
			let $this=this;

			dom_modal.addEventListener('show.bs.modal', function(event) {
				// console.log('modal.show');
				$this.$emit('show');
			});
			dom_modal.addEventListener('shown.bs.modal', function(event) {
				// console.log('modal.shown');
				$this.$emit('shown');	
				
				// Close modal on android back button
				if (typeof(this.dataset.hash) !== 'undefined') 
					history.pushState(null, null, this.dataset.hash)
			});
			// dom_modal.addEventListener('hide.bs.modal', function(event) {
			// 	// console.log('modal.hide');
			// 	$this.$emit('hide');
			// 	// Close modal on android back button
			// 	if (this.dataset.pushback !== 'true') {
			// 		event.preventDefault();
			// 		history.back();
			// 	}
			// 	this.dataset.pushback = '';
			// })
			// dom_modal.addEventListener('hidden.bs.modal', event => {
			// 	// console.log('modal.hiden');
			// 	$this.$emit('hiden');
			// });
		},
		methods: {
			
			show: function() {
				// console.log('Modal.show', this.modalform);				
				this.modalform.show();
			},
			close: function() {
				// console.log('Modal.close');
				this.modalform.hide();
			}
		}
	}
</script>

<style>
	.modal {
		.modal-dialog {
			height: 100%;
			margin: auto;
			display: flex;
    		align-items: center;
			justify-content: center;
		}

		.modal-content {
			height: auto;
			max-height: 90%;
			overflow: hidden;

			padding: 1em 1.5em; 

			@media (min-width: 576px) {
				padding: 1.5em 2em;
			}

			@media (min-width:768px) {
				padding: 2.5em; 
			}
		}

		.modal-header {
		}
		.modal-body {
		}
		.modal-footer {

		}
		.error-label {
			margin-bottom: 0.5rem;
			color: var(--danger-color);
			width: 100%;
		}
			/* .modal-body {
				padding: 1rem 0.5rem;
			} */
		
	}
</style>