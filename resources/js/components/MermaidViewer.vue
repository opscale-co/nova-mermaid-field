<template>
    <div
        class="nova-mermaid-wrapper"
        :style="{ height: height + 'px' }"
        data-testid="mermaid-viewer"
    >
        <div ref="viewport" class="nova-mermaid-viewport">
            <div
                ref="canvas"
                class="nova-mermaid-canvas"
                data-testid="mermaid-canvas"
                :style="{ transform: `scale(${zoom})` }"
            ></div>
        </div>
        <div v-if="zoomControls" class="nova-mermaid-controls" data-testid="mermaid-controls">
            <button type="button" @click.prevent="zoomIn" title="Zoom in" data-testid="mermaid-zoom-in">+</button>
            <button type="button" @click.prevent="zoomOut" title="Zoom out" data-testid="mermaid-zoom-out">−</button>
            <button type="button" @click.prevent="zoomReset" title="Reset" data-testid="mermaid-zoom-reset">⟳</button>
        </div>
    </div>
</template>

<script>
import mermaid from 'mermaid'

let renderSequence = 0

export default {
    props: {
        code: { type: String, default: '' },
        height: { type: Number, default: 600 },
        theme: { type: String, default: 'default' },
        zoomControls: { type: Boolean, default: true },
    },

    data: () => ({
        zoom: 1,
        error: null,
    }),

    mounted() {
        this.render()
    },

    watch: {
        code() {
            this.render()
        },
        theme() {
            this.render()
        },
    },

    methods: {
        async render() {
            if (!this.$refs.canvas || !this.code) return

            mermaid.initialize({
                startOnLoad: false,
                securityLevel: 'strict',
                theme: this.theme,
            })

            try {
                await mermaid.parse(this.code)
                const { svg } = await mermaid.render(`nova-mermaid-${++renderSequence}`, this.code)
                this.$refs.canvas.innerHTML = svg
                this.zoom = 1
                this.error = null
                this.$emit('rendered')
            } catch (err) {
                this.$refs.canvas.innerHTML = ''
                this.error = err
                this.$emit('render-error', err)
            }
        },
        zoomIn() {
            this.zoom = Math.min(this.zoom * 1.25, 8)
        },
        zoomOut() {
            this.zoom = Math.max(this.zoom / 1.25, 0.125)
        },
        zoomReset() {
            this.zoom = 1
        },
    },
}
</script>

<style scoped>
.nova-mermaid-wrapper {
    position: relative;
    width: 100%;
    border: 1px solid rgba(var(--colors-gray-300));
    border-radius: 0.5rem;
    background: #fff;
    overflow: hidden;
}

.nova-mermaid-viewport {
    width: 100%;
    height: 100%;
    overflow: auto;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 1rem;
}

.nova-mermaid-canvas {
    transform-origin: top center;
}

.nova-mermaid-controls {
    position: absolute;
    bottom: 0.75rem;
    right: 0.75rem;
    display: flex;
    gap: 0.25rem;
}

.nova-mermaid-controls button {
    width: 2rem;
    height: 2rem;
    border-radius: 0.375rem;
    background: #fff;
    border: 1px solid rgba(var(--colors-gray-300));
    cursor: pointer;
    font-weight: 600;
    line-height: 1;
}

.nova-mermaid-controls button:hover {
    background: rgba(var(--colors-gray-100));
}
</style>
