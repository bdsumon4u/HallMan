import * as faceapi from "face-api.js";

const video = document.getElementById("video");
const canvas = document.getElementById("canvas");

Promise.all([
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models"),
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
]).then(start);

function start() {
    navigator.getUserMedia(
        { video: {} },
        (stream) => {
            video.srcObject = stream;
        },
        (err) => {
            console.error(err);
        }
    );

    recognizeFaces();
}

async function recognizeFaces() {
    const labeledDescriptors = await loadLabels();
    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);

    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
        const detections = await faceapi
            .detectAllFaces(video)
            .withFaceLandmarks()
            .withFaceDescriptors();
        const resizedDetections = faceapi.resizeResults(
            detections,
            displaySize
        );
        canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
        const results = resizedDetections.map((d) =>
            faceMatcher.findBestMatch(d.descriptor)
        );

        console.log(results);

        results.forEach((bestMatch, i) => {
            const box = resizedDetections[i].detection.box;
            const drawBox = new faceapi.draw.DrawBox(box, {
                label: bestMatch.toString(),
            });
            drawBox.draw(canvas);
            console.log(bestMatch.label);
        });
    }, 350);
}

function loadLabels() {
    const labels = ["bdsumon4u", "khadiza"];

    return Promise.all(labels.map(async (label) => {
        const descriptions = [];
        for (let i = 1; i <= 2; i++) {
            const img = await faceapi.fetchImage(`storage/${label}/${i}.png`);
            const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
            descriptions.push(detections.descriptor);
        }

        return new faceapi.LabeledFaceDescriptors(label, descriptions);
    }));
}
